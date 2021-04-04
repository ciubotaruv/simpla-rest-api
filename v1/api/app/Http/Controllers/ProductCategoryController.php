<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Product as Product;
use App\Features;
use App\Category;
use App\ProductCategory as ProductCategory;
class ProductCategoryController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $products = ProductCategory::with(['category','products']);

        if ($request->has('category_id')) {
            $products->where('category_id', $request->category_id);
        }

//        if ($request->has('parent_id')) {
//            $get_products->where('parent_id', $request->parent_id);
//        }
        $get_products = $products->with(['category','products'])->get();
        if ($get_products != null) {
            return response()->json($get_products, 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find this query' . $get_products], 400);
        }

		//return view('home');
	}

}
