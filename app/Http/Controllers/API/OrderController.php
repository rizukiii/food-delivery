<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JsonResponses;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        try {
            $request->validate([
                'delivery_address_id' => 'required|exists:addresses,id',
                'order_note' => 'nullable|string',
            ]);

            $cartItems = Cart::where('user_id', auth()->id())->get();

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_amount' => $cartItems->sum(fn($item) => $item->quantity * $item->price),
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'delivery_address_id' => $request->delivery_address_id,
                'order_note' => $request->order_note,
                'order_payment' => $request->order_payment,
            ]);

            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'total_price' => $item->price,
                ]);
            }

            Cart::where('user_id', auth()->id())->delete();

            return new JsonResponses(Response::HTTP_CREATED, 'Order placed successfully', $order);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_OK, 'Something went wrong', null, ['error' => $e->getMessage()]);
        }
    }

    public function getOrders()
    {
        try {
            $orders = Order::where('user_id', auth()->id())->with('details.product')->get();

            return new JsonResponses(Response::HTTP_OK, 'Orders retrieved successfully', $orders);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_OK, 'Something went wrong', null, ['error' => $e->getMessage()]);
        }
    }
}
