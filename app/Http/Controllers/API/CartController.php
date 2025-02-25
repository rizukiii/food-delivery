<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JsonResponses;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $cart = Cart::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $request->product_id],
                ['quantity' => $request->quantity]
            );

            return new JsonResponses(Response::HTTP_OK, 'Item added to cart', $cart);
        } catch (Exception $e) {
            return new JsonResponses(Response::HTTP_OK, 'Something went wrong', null, ['error' => $e->getMessage()]);
        }
    }

    public function getCart()
    {
        try {
            $cart = Cart::where('user_id', Auth::id())->with('product')->get();

            return new JsonResponses(Response::HTTP_OK, 'Cart fetched successfully', $cart);
        } catch (Exception $e) {
            return new JsonResponses(Response::HTTP_OK, 'Something went wrong', null, ['error' => $e->getMessage()]);
        }
    }

    public function removeFromCart($id)
    {
        try {
            $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->first();

            $cartItem->delete();
            
            return new JsonResponses(Response::HTTP_OK, 'Item removed from cart',null);
        } catch (Exception $e) {
            return new JsonResponses(Response::HTTP_OK, 'Something went wrong', null, ['error' => $e->getMessage()]);
        }
    }
}
