<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Product;
use App\Traits\S3StorageTrait;

class ImageService
{
    use S3StorageTrait;

    public function upload(array $data): void
    {
        $product = Product::find($data['product_id']);

        if (! $product) {
            throw new \Exception('Producto no encontrado.', 404);
        }

        $uploaded_images = [];

        foreach ($data['images'] as $file) {
            $path = $this->uploadFile(
                $file->get(), 
                "products/{$product->id}", 
                $file->getClientOriginalName(),
                'public'
            );

            $uploaded_images[] = Image::create([
                'name' => $file->getClientOriginalName(),
                'url' => $this->getFileUrl($path),
                'path' => $path,
                'product_id' => $product->id,
            ]);
        }
    }
}
