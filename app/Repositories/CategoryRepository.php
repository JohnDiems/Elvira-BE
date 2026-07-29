<?php

namespace App\Repositories;

use App\Interface\Repositories\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all()
    {
        return Category::all();
    }
}
