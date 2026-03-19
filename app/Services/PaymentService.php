<?php

namespace App\Services;

use App\Models\Payment;
use App\Traits\ServerSideFiltersTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentService
{
    use ServerSideFiltersTrait;

    public function showAll(): LengthAwarePaginator
    {
        $query = Payment::with([
            'order:id,status,total_products,total_price,user_id',
        ])
            ->select(
                'payments.id',
                'payments.status',
                'payments.method',
                'payments.quantity',
                'payments.order_id',
                'payments.created_at',
                'payments.updated_at'
            );

        $this->applyServerSideFilters($query, request()->input('filters', []));
        $this->applyServerSideSort($query, 'payments.updated_at', 'desc');

        return $query->paginate(request()->input('per_page', 10));
    }

    public function createOrUpdate(array $data): void
    {
        Payment::updateOrCreate(['id' => $data['id'] ?? null], $data);
    }

    public function delete(Payment $payment): void
    {
        $payment->delete();
    }
}
