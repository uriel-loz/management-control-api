<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\ServerSideFiltersTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductService
{
    use ServerSideFiltersTrait;

    public function showAll(): LengthAwarePaginator
    {
        $query = Product::with([
            'categories:id,name,slug',
        ])
            ->select(
                'products.id',
                'products.name',
                'products.slug',
                'products.price',
                'products.quantity',
                'products.description',
                'products.created_at',
                'products.updated_at',
                DB::raw('(SELECT path FROM images WHERE images.product_id = products.id AND images.deleted_at IS NULL ORDER BY images.created_at ASC LIMIT 1) as main_image')
            );

        $custom_filters = [
            'price' => function ($query, $value) {
                $query->where('products.price', '<=', (float) $value);
            },
        ];

        $this->applyServerSideFilters($query, request()->input('filters', []), $custom_filters);
        $this->applyServerSideSort($query, 'products.updated_at', 'desc');

        return $query->paginate(request()->input('per_page', 10));
    }

    public function showOne(Product $product): Product
    {
        return $product->load([
            'categories:id,name,slug',
            'images:id,name,path,product_id',
        ]);
    }

    public function create(array $data): Product
    {
        $category_ids = $data['category_ids'];
        unset($data['category_ids']);

        $product = Product::create($data);
        $product->categories()->sync($category_ids);

        return $product->load('categories:id,name,slug');
    }

    public function update(Product $product, array $data): Product
    {
        $category_ids = $data['category_ids'];
        unset($data['category_ids']);

        $product->update($data);
        $product->categories()->sync($category_ids);

        return $product->fresh()->load('categories:id,name,slug');
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
