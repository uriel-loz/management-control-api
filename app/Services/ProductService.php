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
            DB::raw('(SELECT url 
                FROM images 
                WHERE images.product_id = products.id 
                AND images.deleted_at IS NULL 
                ORDER BY images.created_at ASC 
                LIMIT 1) as main_image'
            )
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

    public function create(array $data): Product
    {
        $categories = $data['categories'];
        unset($data['categories']);

        $data['slug'] = $this->generateSlug($data['name']);

        $product = Product::create($data);
        $product->categories()->sync($categories);

        return $product->load('categories:id,name,slug');
    }

    public function update(Product $product, array $data): Product
    {
        $categories = $data['categories'];
        unset($data['categories']);

        $data['slug'] = $this->generateSlug($data['name']);

        $product->update($data);
        $product->categories()->sync($categories);

        return $product->fresh()->load('categories:id,name,slug');
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    private function generateSlug(string $value): string
    {
        return preg_replace(
            '/\s+/',
            '-',
            trim(
                preg_replace(
                    '/[^a-z0-9\s-]/',
                    '',
                    preg_replace(
                        '/[\x{0300}-\x{036f}]/u',
                        '',
                        mb_strtolower($value, 'UTF-8')
                    )
                )
            )
        );
    }
}
