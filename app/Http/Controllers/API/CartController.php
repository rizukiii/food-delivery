<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JsonResponses;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
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
                'is_exist' => 'required|boolean',
            ]);

            $product = Product::find($request->product_id);
            $image_product = is_array($product->image) ? $product->image[0] : null;

            $cart = Cart::updateOrCreate(
                ['user_id' => auth()->id(), 'product_id' => $product->id], // Kondisi pencarian
                [
                    'quantity' => $request->quantity,
                    'price' => $product->price * $request->quantity,
                    'image' => $image_product,
                    'is_exist' => $request->is_exist,
                ] // Data yang akan diperbarui atau dibuat
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

            return new JsonResponses(Response::HTTP_OK, 'Item removed from cart', null);
        } catch (Exception $e) {
            return new JsonResponses(Response::HTTP_OK, 'Something went wrong', null, ['error' => $e->getMessage()]);
        }
    }
}
