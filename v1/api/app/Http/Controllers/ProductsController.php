<?php

namespace App\Http\Controllers;

use App\Product as Product;
require_once (BASE_PATH . '/api/Simpla.php');

class ProductsController extends Controller
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
        $simpla = new \Simpla();
        $get_products = Product::with(['category', 'brands', 'images', 'variants', 'image'])->get()->toArray();
        $path = $_SERVER['HTTP_HOST'] . '/files/products/';

        foreach ($get_products as $k => $product) {
            foreach ($product['image']as $key_img => $images){
                $get_products[$k]['image'][0]['small'] = $simpla->design->resize_modifier($images['filename'],200,200,false,false);
                $get_products[$k]['image'][0]['medium'] = $simpla->design->resize_modifier($images['filename'],500,500,false,false);
                $get_products[$k]['image'][0]['large'] = $simpla->design->resize_modifier($images['filename'],800,800,false,false);
                $get_products[$k]['image'][0]['extra'] = $simpla->design->resize_modifier($images['filename'],1200,1200,false,false);
            }
            foreach ($product['images'] as $key_img_images => $images){
              //  dd($get_products[$k]['images'][$key_img_images]['filename']);
                $get_products[$k]['images'][$key_img_images]['small'] = $simpla->design->resize_modifier($images['filename'],200,200,false,false);
                $get_products[$k]['images'][$key_img_images]['medium'] = $simpla->design->resize_modifier($images['filename'],500,500,false,false);
                $get_products[$k]['images'][$key_img_images]['large'] = $simpla->design->resize_modifier($images['filename'],800,800,false,false);
                $get_products[$k]['images'][$key_img_images]['extra'] = $simpla->design->resize_modifier($images['filename'],1200,1200,false,false);
            }
        }

        return response()->json($get_products, 200);

    }

    public function getOne($id) {

        $get_product = Product::with(['category', 'brands', 'images', 'variants', 'image'])->where('id',$id)->get()->toArray();
        return response()->json($get_product, 200);

    }

    public function getByID($id)
    {
        $product = Product::with(['category', 'brands', 'images', 'variants', 'image'])->where('id', '=', $id)->get()->toArray();

        if ($product != null) {
            return response()->json($product, 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find product with ID ' . $id], 400);
        }


    }

    public function getByUrl($url)
    {
        $product = Product::with(['category', 'brands', 'images', 'variants', 'image'])->where('url', '=', $url)->get()->toArray();

        if ($product != null) {
            return response()->json($product, 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find product with URL ' . $url], 400);
        }


    }
    public function getByID2($id)
    {
        // Search for a user based on their name.
        $user = Product::query();
        // Search for a user based on their company.

        if ($id) {
            $user->where('visible', 1)->get();
        }
        if ($id) {
            $user->where('position', 42)->get();
        }
        if ($id) {
            $user->whereHas('brands', function ($query) {
                return $query->where('name', '=', 'Samsung');
            })->get();
        }

        return $user->with(['category', 'brands', 'images', 'variants', 'image'])->get();


    }

    public function getBrandProduct($id)
    {
        $get_brand = Product::with(['category', 'brands', 'images', 'variants', 'image'])->where('brand_id', '=', $id)->get()->toArray();

        if ($get_brand != null) {
            return response()->json($get_brand, 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find product with brand id ' . $id], 400);
        }
    }

    public function getCategoryProduct($id)
    {

        $get_category = Product::with(['category', 'brands', 'images', 'variants', 'image'])->whereHas('category_id', function ($query) {
            return $query->where('category_id', '=', $id);
        })->get();

        return response()->json($get_category);
    }

    public function getFeatured($id)
    {
        $get_featured = Product::with(['category', 'brands', 'images', 'variants', 'image'])->where('featured', '=', $id)->get();
        return response()->json($get_featured);
    }

    public function getVisible($id)
    {
        $get_visible = Product::with(['category', 'brands', 'images', 'variants', 'image'])->where('visible', '=', $id)->get();
        return response()->json($get_visible);
    }


}
