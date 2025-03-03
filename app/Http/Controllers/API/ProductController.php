<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JsonResponses;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('productType')->get();

            $products->transform(function ($item) {
                if (is_array($item->image)) {
                    $item->image = array_map(function ($image) {
                        return url('/') . Storage::url($image);
                    }, $item->image);
                }
                return $item;
            });

            return new JsonResponses(Response::HTTP_OK, 'Semua produk berhasil didapatkan!', $products);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong',  ['error' => $e->getMessage()]);
        }
    }

    public function detail($id)
    {
        try {
            $product = Product::findOrFail($id);

            $product->image = array_map(function ($image) {
                return url('/') . Storage::url($image);
            },$product->image);

            return new JsonResponses(Response::HTTP_OK, 'Satu Data produk berhasil didapatkan!', $product);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong', ['error' => $e->getMessage()]);
        }
    }
}
