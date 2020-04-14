<?php


namespace App\Http\Controllers\API;

use App\Http\Resources\ProductResource;
use App\Http\Service\AppbaseService;
use App\Models\Product;

class ProductController extends AppbaseService
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $products = Product::all();
        return $this->sendResponseWithoutMsg(ProductResource::collection($products));
    }
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $product = Product::find((int)$id)->first();
        if (!$product) {
            return $this->sendError("Product $id not found ");
        }
        return $this->sendResponseWithoutMsg(new ProductResource($product));
    }
}
