<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JsonResponses;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        try {
            $request->validate([
                'delivery_address_id' => 'required|exists:addresses,id',
                'order_payment' => 'required|in:cash,midtrans',
                'order_note' => 'nullable|string',
                'schedule_at' => 'nullable|date|after:now',
            ]);

            $user = auth()->user(); // ✅ Perbaikan di sini
            $cartItems = Cart::where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return new JsonResponses(Response::HTTP_BAD_REQUEST, 'Cart is empty', []);
            }

            // Hitung order amount & tax
            $order_amount = $cartItems->sum(fn($item) => $item->quantity * $item->price);
            $total_tax = $order_amount * 0.10;
            $delivery_charge = 10.0;
            $otp = rand(100000, 999999);
            $scheduled = $request->has('schedule_at');

            // Buat order
            $order = Order::create([
                'user_id' => $user->id,
                'order_amount' => $order_amount,
                'order_payment' => $request->order_payment,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'total_tax_amount' => $total_tax,
                'order_note' => $request->order_note,
                'delivery_charge' => $delivery_charge,
                'schedule_at' => $request->schedule_at,
                'otp' => $otp,
                'refund_requested' => false,
                'refunded' => false,
                'scheduled' => $scheduled,
                'delivery_address_id' => $request->delivery_address_id,
                'details_count' => $cartItems->sum('quantity'),
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

            // Hapus Cart setelah order dibuat
            Cart::where('user_id', $user->id)->delete();

            // ✅ Tambah order_count di User
            $user = User::find(auth()->user());
            $user->increment('order_count');

            return new JsonResponses(Response::HTTP_CREATED, 'Order placed successfully', $order);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong', [], ['error' => $e->getMessage()]);
        }
    }


    public function getOrders()
    {
        try {

            $user = User::find(auth()->user());

            $orders = Order::where('user_id', $user->id)->with('details.product')->get();

            return new JsonResponses(Response::HTTP_OK, 'Orders retrieved successfully', $orders);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong', [], ['error' => $e->getMessage()]);
        }
    }
}
