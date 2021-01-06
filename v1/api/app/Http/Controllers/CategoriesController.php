<?php

namespace App\Http\Controllers;

use App\Category;

class CategoriesController extends Controller
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
    public function getAll()
    {
        $get_products = Category::all()->toArray();

        if ($get_products != null) {
            return response()->json($get_products, 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find brand with name ' . $get_products], 400);
        }
    }

    public function getById($id)
    {
        $get_products = Category::where('id', '=', $id)->get();
        if ($get_products != null) {
            return response()->json($get_products, 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find category with ID ' . $id], 400);
        }
    }

    public function getByProduct($id)
    {
        $get_products = Category::with('products')->where('id', '=', $id)->get();
        if ($get_products != null) {
            return response()->json($get_products, 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find category with ID ' . $id], 400);
        }
    }


}
