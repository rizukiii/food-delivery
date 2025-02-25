<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JsonResponses;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('productType')->get()->map(function ($product) {
                // Menambahkan base URL ke gambar
                $product->image = $product->image ? URL::to('/storage/' . $product->image) : null;
                return $product;
            });

            return new JsonResponses(Response::HTTP_OK, 'Semua produk berhasil didapatkan!', $products);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong', null, ['error' => $e->getMessage()]);
        }
    }
}
