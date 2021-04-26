<?php

namespace App\Http\Controllers;

use App\ProductCategory as ProductCategory;
use Illuminate\Http\Request;

require_once(BASE_PATH . '/api/Simpla.php');

class ProductCategoryController extends Controller
{

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
        $products_with_category = ProductCategory::with(['category']);
        $products_with_category_parent = ProductCategory::with(['category']);
        $products_with_only_category = ProductCategory::with(['category']);

        if ($request->has('category_id')) {
            $parent_d = $request->category_id;
            $products_with_category->whereHas('category', function ($query) use ($parent_d) {
                $query->where('category_id', '=', $parent_d)->where('parent_id', '=', 0);
            });
            $products_with_only_category->whereHas('category', function ($query) use ($parent_d) {
                $query->where('category_id', '=', $parent_d);
            });

            $products_with_category_parent->whereHas('category', function ($query) use ($parent_d) {
                $query->where('parent_id', '=', $parent_d);
            }
            );
        }



        $get_products = $products_with_category->with(['category', 'products'])->get();
        $get_products2 = $products_with_category_parent->with(['category', 'products'])->get();
        $get_products3 = $products_with_only_category->with(['category', 'products'])->get();
        $all_products_from_category = [];

        foreach ($get_products as $k => $item) {
            $all_products_from_category[] = $item;
        }

        foreach ($get_products2 as $k => $item) {
            $all_products_from_category[] = $item;
        }

        foreach ($get_products3 as $k => $item) {
            $all_products_from_category[] = $item;
        }

        if ($request->has('brand_id')) {
            $all_products_from_category->whereHas('products', function ($query) use ($request) {
                $query->where('brand_id', $request->brand_id);
            });
        }

        // dd($all_products_from_category);
        $simpla = new \Simpla();
        foreach ($all_products_from_category as $k => $product) {
            //dd($product);

            foreach ($product->products->options as $key => $items) {
                $all_products_from_category[$k]['products']['options'][$key]['name'] = $items->features->name;
                $all_products_from_category[$k]['products']['options'][$key]['in_filter'] = $items->features->in_filter;
            }

            foreach ($product->products->image as $key_img => $images) {
                $all_products_from_category[$k]['products']['image'][0]['small'] = $simpla->design->resize_modifier($images['filename'], 200, 200, false, false);
                $all_products_from_category[$k]['products']['image'][0]['medium'] = $simpla->design->resize_modifier($images['filename'], 500, 500, false, false);
                $all_products_from_category[$k]['products']['image'][0]['large'] = $simpla->design->resize_modifier($images['filename'], 800, 800, false, false);
                $all_products_from_category[$k]['products']['image'][0]['extra'] = $simpla->design->resize_modifier($images['filename'], 1200, 1200, false, false);
            }
            foreach ($product->products->images as $key_img_images => $images) {
                $all_products_from_category[$k]['products']['images'][$key_img_images]['small'] = $simpla->design->resize_modifier($images['filename'], 200, 200, false, false);
                $all_products_from_category[$k]['products']['images'][$key_img_images]['medium'] = $simpla->design->resize_modifier($images['filename'], 500, 500, false, false);
                $all_products_from_category[$k]['products']['images'][$key_img_images]['large'] = $simpla->design->resize_modifier($images['filename'], 800, 800, false, false);
                $all_products_from_category[$k]['products']['images'][$key_img_images]['extra'] = $simpla->design->resize_modifier($images['filename'], 1200, 1200, false, false);
            }
        }


        // $get_products = $products->with(['category','products'])->get();

        if ($all_products_from_category != null) {
            // dd(response()->json($get_products, 200));
            // return response()->json($get_products, 200);
            return response()->json(array_unique($all_products_from_category), 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find this query' . $get_products], 400);
        }
        //return response()->json($products, 200);
        //return view('home');
    }

}
