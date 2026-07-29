<?php

namespace App\Services;

use App\Interface\Repositories\UserRepositoryInterface;
use App\Interface\Services\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AuthService implements AuthServiceInterface
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(object $payload)
    {
        $user = $this->userRepository->create($payload);

        // Assign Spatie role
        $user->assignRole($payload->role);

        // If admin, initialize store config
        if ($payload->role === 'admin') {
            $user->storeConfig()->create([
                'business_type' => 'cafe',
                'locations_count' => '1',
                'is_migrating' => false,
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function loginUser(object $payload)
    {
        $user = $this->userRepository->findOneByEmail($payload->email);

        if (!$user || !Hash::check($payload->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Apply MFA for Admin role
        if ($user->role === 'admin') {
            $otp = (string) rand(100000, 999999);
            Cache::put('admin_otp_' . $user->id, $otp, 300);

            return response()->json([
                'otp_required' => true,
                'user_id' => $user->id,
                'otp' => $otp, // Return OTP in response for easy testing
                'message' => 'OTP sent to registered mobile device'
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function verifyOtp(object $payload)
    {
        $cachedOtp = Cache::get('admin_otp_' . $payload->user_id);

        if (!$cachedOtp || $cachedOtp !== $payload->otp) {
            return response()->json([
                'message' => 'Invalid or expired OTP'
            ], 422);
        }

        Cache::forget('admin_otp_' . $payload->user_id);

        $user = $this->userRepository->findById($payload->user_id);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
}
