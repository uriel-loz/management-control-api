<?php

namespace App\Services;

use App\Models\Category;
use App\Traits\ServerSideFiltersTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    use ServerSideFiltersTrait;

    public function showAll(): LengthAwarePaginator
    {
        $query = Category::withCount('products');

        $this->applyServerSideFilters($query, request()->input('filters', []));
        $this->applyServerSideSort($query, 'updated_at', 'desc');

        return $query->paginate(request()->input('per_page', 10));
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->fresh();
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
