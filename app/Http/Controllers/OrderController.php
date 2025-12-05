<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function confirm(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
        ]);

        $user = auth()->user();
        $customerId = $user->customer->id; // your users are linked to customers

        DB::beginTransaction();

        try {
            // compute total
            $total = array_sum(array_column($request->items, 'price'));

            // create order
            $order = Order::create([
                'customer_id'   => $customerId,
                'total_amount'  => $total,
                'order_status'  => 'completed',
                'payment_status' => 'paid',
            ]);

            // loop through items
            foreach ($request->items as $item) {

                $product = Product::where('name', $item['name'])->first();

                if (!$product) continue;

                // save order detail
                OrderDetail::create([
                    'order_id'  => $order->id,
                    'product_id' => $product->id,
                    'quantity'  => 1, // your system doesn't track quantity per item yet
                    'unit_price' => $product->unit_price,
                    'subtotal'  => $product->unit_price,
                ]);

                // reduce inventory
                $inventory = Inventory::where('product_id', $product->id)->first();

                if ($inventory) {
                    $inventory->stock_quantity = max(0, $inventory->stock_quantity - 1);
                    $inventory->save();
                }
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
