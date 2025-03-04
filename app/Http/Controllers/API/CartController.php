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
            // ambil product berdasar user
            $product = Product::find($request->product_id);
            // ambil foto produk yang pertama
            $image_product = is_array($product->image) ? $product->image[0] : null;
            // cari cart berdasar user dan product nya bedasar id
            $cart = Cart::where('user_id', auth()->id())->where('product_id', $product->id)->first();

            // jika ada product maka update
            if ($cart) {
                if ($request->quantity > $product->quantity) {
                    return new JsonResponses(Response::HTTP_BAD_REQUEST, 'Stock tidak mencukupi!', []);
                }

                // Update quantity, harga, dan is_exist
                $cart->update([
                    'quantity' => $newQuantity,
                    'price' => $product->price * $newQuantity,
                    'image' => $image_product,
                    'is_exist' => $request->is_exist,
                ]);
                $cart->save();
            } else {
                // jika item belum ada di cart buat baru
                if ($request->quantity > $product->stock) {
                    return new JsonResponses(Response::HTTP_BAD_REQUEST, 'Stock tidak mencukupi!', []);
                }

                $cart = Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->price * $request->quantity,
                    'image' => $image_product,
                    'is_exist' => $request->is_exist
                ]);
            }
            return new JsonResponses(Response::HTTP_OK, 'Item added/updated to cart', $cart);
        } catch (Exception $e) {
            return new JsonResponses(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong',  ['error' => $e->getMessage()]);
        }
    }

    public function getCart(Request $request)
    {
        try {
            $show_only_selected = $request->query('selected', false);

            $cart_query = Cart::where('user_id', auth()->id())->with('product');

            if ($show_only_selected) {
                $cart_query->where('is_exists', true);
            }

            $cart = $cart_query->get();

            $total_price = $cart->where('is_exist', true)->sum('price');

            return new JsonResponses(Response::HTTP_OK, 'Cart fetched successfully', [
                'item' => $cart,
                'total_price' => $total_price,
            ]);
        } catch (Exception $e) {
            return new JsonResponses(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong',  ['error' => $e->getMessage()]);
        }
    }

    public function removeFromCart($id)
    {
        try {
            $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->first();

            if (!$cartItem) {
                return new JsonResponses(Response::HTTP_NOT_FOUND, 'Data tidak ada!', []);
            }

            $cartItem->delete();

            return new JsonResponses(Response::HTTP_OK, 'Data item berhasil dihapus!', []);
        } catch (Exception $e) {
            return new JsonResponses(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong', ['error' => $e->getMessage()]);
        }
    }
}
