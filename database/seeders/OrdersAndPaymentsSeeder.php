<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrdersAndPaymentsSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── Load required data ────────────────────────────────────────────────
        $customer_ids = User::where('is_customer', true)->pluck('id')->toArray();
        $products     = Product::all(['id', 'price', 'slug'])->toArray();

        // Separate products into bestsellers (0-5), medium (6-15), rare (16-29)
        $bestsellers = array_slice($products, 0, 6);
        $medium      = array_slice($products, 6, 10);
        $rare        = array_slice($products, 16);

        // Weighted product pool: bestsellers appear 5x, medium 2x, rare 1x
        $weighted_pool = array_merge(
            array_merge($bestsellers, $bestsellers, $bestsellers, $bestsellers, $bestsellers),
            array_merge($medium, $medium),
            $rare
        );

        // ── Month definitions: [year, month, order_count] ────────────────────
        // Sept 2025 → Mar 2026 with realistic volume variation
        $months = [
            [2025, 9,  15],  // Septiembre — arranque lento
            [2025, 10, 18],  // Octubre — crecimiento moderado
            [2025, 11, 22],  // Noviembre — Black Friday acercándose
            [2025, 12, 35],  // Diciembre — pico navideño
            [2026, 1,  28],  // Enero — rebajas post-navidad
            [2026, 2,  20],  // Febrero — San Valentín
            [2026, 3,  15],  // Marzo — normalización
        ];

        // ── Order status distribution (out of 100) ────────────────────────────
        // completed=75, processing=10, pending=8, cancelled=5, refunded=2
        $order_statuses = array_merge(
            array_fill(0, 75, 'completed'),
            array_fill(0, 10, 'processing'),
            array_fill(0, 8,  'pending'),
            array_fill(0, 5,  'cancelled'),
            array_fill(0, 2,  'refunded')
        );

        // ── Payment method distribution (out of 100) ─────────────────────────
        // credit_card=40, debit_card=25, paypal=20, bank_transfer=10, cash=5
        $payment_methods = array_merge(
            array_fill(0, 40, 'credit_card'),
            array_fill(0, 25, 'debit_card'),
            array_fill(0, 20, 'paypal'),
            array_fill(0, 10, 'bank_transfer'),
            array_fill(0, 5,  'cash')
        );

        // ── Build all records ─────────────────────────────────────────────────
        $orders         = [];
        $order_products = [];
        $payments       = [];

        foreach ($months as [$year, $month, $count]) {
            $month_start = Carbon::create($year, $month, 1, 0, 0, 0);
            $month_end   = $month_start->copy()->endOfMonth();

            for ($i = 0; $i < $count; $i++) {
                // Random timestamp within the month
                $created_at = Carbon::createFromTimestamp(
                    rand($month_start->timestamp, $month_end->timestamp)
                )->toDateTimeString();

                // Pick random customer and order status
                $user_id      = $customer_ids[array_rand($customer_ids)];
                $order_status = $order_statuses[array_rand($order_statuses)];

                // Pick 1-4 unique products for this order (Pareto-weighted pool)
                $num_products  = rand(1, 4);
                $selected_keys = array_rand($weighted_pool, min($num_products, count($weighted_pool)));
                if (!is_array($selected_keys)) {
                    $selected_keys = [$selected_keys];
                }

                // De-duplicate by product id in case weighted pool repeated entries
                $seen_product_ids = [];
                $order_lines      = [];
                foreach ($selected_keys as $key) {
                    $product = $weighted_pool[$key];
                    if (!in_array($product['id'], $seen_product_ids)) {
                        $seen_product_ids[] = $product['id'];
                        $order_lines[]      = $product;
                    }
                }

                // Calculate order totals
                $total_products = count($order_lines);
                $total_price    = array_sum(array_column($order_lines, 'price'));

                // Create order record
                $order_id = Str::uuid()->toString();

                $orders[] = [
                    'id'             => $order_id,
                    'status'         => $order_status,
                    'total_products' => $total_products,
                    'total_price'    => number_format($total_price, 2, '.', ''),
                    'user_id'        => $user_id,
                    'created_at'     => $created_at,
                    'updated_at'     => $created_at,
                ];

                // Create order_product pivot records
                foreach ($order_lines as $product) {
                    $order_products[] = [
                        'order_id'   => $order_id,
                        'product_id' => $product['id'],
                        'created_at' => $created_at,
                        'updated_at' => $created_at,
                    ];
                }

                // Map order status → payment status
                $payment_status = match ($order_status) {
                    'completed'  => 'completed',
                    'processing' => 'processing',
                    'pending'    => 'pending',
                    'cancelled'  => 'failed',
                    'refunded'   => 'refunded',
                    default      => 'pending',
                };

                $payment_method = $payment_methods[array_rand($payment_methods)];

                $payments[] = [
                    'id'         => Str::uuid()->toString(),
                    'status'     => $payment_status,
                    'method'     => $payment_method,
                    'quantity'   => number_format($total_price, 2, '.', ''),
                    'order_id'   => $order_id,
                    'created_at' => $created_at,
                    'updated_at' => $created_at,
                ];
            }
        }

        // ── Bulk insert in correct dependency order ────────────────────────────
        Order::insert($orders);
        DB::table('order_product')->insert($order_products);
        Payment::insert($payments);
    }
}
