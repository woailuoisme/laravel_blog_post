<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Auth;

class OrderController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $orders=Auth::guard('api')->user()->orders()->with('products')->get();
        return $this->sendResponse(OrderResource::collection($orders), 'Orders retrieved successfully');
    }

}
