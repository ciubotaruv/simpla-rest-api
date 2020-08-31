<?php

namespace App\Http\Controllers;

//use App\Http\Requests\Request;
use Illuminate\Http\Request;

use App\Order as Cart;

class CartController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Welcome Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "marketing page" for the application and
    | is configured to only allow guests. Like most of the other sample
    | controllers, you are free to modify or remove it as you desire.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function getAllOrders()
    {
        $get_orders = Cart::with(['user', 'delivery', 'payment_method'])->get()->toArray();
        //$get_products = Brand::lists('name');
        return response()->json($get_orders);
    }

    public function getById($id)
    {
        $get_products = Cart::with(['user', 'delivery', 'payment_method'])->where('id', '=', $id)->get();
        return response()->json($get_products);
    }

    public function getPaid($number)
    {
        $get_paid = Cart::with(['user', 'delivery', 'payment_method'])->where('paid', '=', $number)->get();
        return response()->json($get_paid);
    }

    public function getClosed($number)
    {
        $get_closed = Cart::with(['user', 'delivery', 'payment_method'])->where('closed', '=', $number)->get();
        return response()->json($get_closed);
    }
    public function insertOrder(Request $request)
    {

       $order = Cart::create($request->all());
        return response()->json($order,201);
    }

}
