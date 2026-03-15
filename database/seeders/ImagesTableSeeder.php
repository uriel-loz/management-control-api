<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;

class ImagesTableSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $now = now()->toDateTimeString();

        $products = Product::all(['id', 'slug']);

        $images = [];

        // Number of images per product index (cyclical pattern: 2,3,2,1,3,2...)
        $image_counts = [2, 3, 2, 1, 3, 2, 2, 3, 1, 2, 3, 2, 2, 1, 3, 2, 2, 3, 2, 1, 2, 3, 2, 2, 1, 3, 2, 2, 3, 2];

        foreach ($products as $index => $product) {
            $count = $image_counts[$index] ?? 2;

            for ($i = 1; $i <= $count; $i++) {
                $images[] = [
                    'id'         => Str::uuid()->toString(),
                    'name'       => $product->slug . '-' . $i . '.jpg',
                    'url'       => 'products/' . $product->slug . '/' . $product->slug . '-' . $i . '.jpg',
                    'product_id' => $product->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        Image::insert($images);
    }
}
