<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ServerSideFiltersTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    use ServerSideFiltersTrait;

    public function showAll(): LengthAwarePaginator
    {
        $query = Order::with([
            'user:id,name,email',
            'products:id,name,price',
            'payment:id,status,method,quantity,order_id',
        ])
        ->select(
            'orders.id',
            'orders.status',
            'orders.total_products',
            'orders.total_price',
            'orders.user_id',
            'orders.created_at',
            'orders.updated_at'
        );

        $this->applyServerSideFilters($query, request()->input('filters', []));
        $this->applyServerSideSort($query, 'orders.updated_at', 'desc');

        return $query->paginate(request()->input('per_page', 10));
    }

    public function find(string $id): Order
    {
        return Order::with([
            'user:id,name,email',
            'products:id,name,price',
            'payment:id,status,method,quantity,order_id',
        ])->findOrFail($id);
    }

    public function createOrUpdate(array $data): void
    {
        $products = $data['products'];
        unset($data['products']);

        $product_ids = array_column($products, 'id');
        $prices = Product::whereIn('id', $product_ids)->pluck('price', 'id');

        $sync_data = [];
        $total_products = 0;
        $total_price = 0;

        foreach ($products as $product) {
            $unit_price = (float) $prices[$product['id']];
            $quantity = (int) $product['quantity'];
            $subtotal = $unit_price * $quantity;

            $sync_data[$product['id']] = [
                'quantity' => $quantity,
                'unit_price' => $unit_price,
                'subtotal' => $subtotal,
            ];

            $total_products += $quantity;
            $total_price += $subtotal;
        }

        $data['total_products'] = $total_products;
        $data['total_price'] = $total_price;

        $order = Order::updateOrCreate(['id' => $data['id'] ?? null], $data);
        $order->products()->sync($sync_data);
    }

    public function cancel(Order $order): void
    {
        $order->update(['status' => OrderStatus::CANCELLED]);
    }

    public function delete(Order $order): void
    {
        $order->delete();
    }
}
