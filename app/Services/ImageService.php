<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class ImageService
{
    public function upload(array $data): Collection
    {
        $product = Product::find($data['product_id']);

        if (! $product) {
            throw new \Exception('Producto no encontrado.', 404);
        }

        $uploaded_images = [];

        /** @var UploadedFile $file */
        foreach ($data['images'] as $file) {
            $path = $file->store("products/{$product->id}", 'public');

            $uploaded_images[] = Image::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'product_id' => $product->id,
            ]);
        }

        return new Collection($uploaded_images);
    }
}
