<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

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
    public function getAll(Request $request)
    {
        $get_products = Category::query();

        if ($request->has('category_id')) {
            $get_products->where('id', $request->category_id);
        }

        if ($request->has('parent_id')) {
            $get_products->where('parent_id', $request->parent_id);
        }

        if ($get_products != null) {
            return response()->json($get_products->get(), 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find this query' . $get_products], 400);
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
