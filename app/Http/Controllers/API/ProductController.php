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
            $products = Product::with('productType')->get();

            return new JsonResponses(Response::HTTP_OK, 'Semua produk berhasil didapatkan!', $products);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_OK, 'Something went wrong', null, ['error' => $e->getMessage()]);
        }
    }
}
