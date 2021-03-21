<?php

namespace App\Http\Controllers;


use App\Product as Product;
use Illuminate\Http\Request;

require_once(BASE_PATH . '/api/Simpla.php');

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
    public function getAll(Request $request)
    {
        $simpla = new \Simpla();
        $get_products = Product::with(['category', 'brands', 'images', 'variants', 'image'])->get();
        $path = $_SERVER['HTTP_HOST'] . '/files/products/';

        foreach ($get_products as $k => $product) {
            foreach ($product['image'] as $key_img => $images) {
                $get_products[$k]['image'][0]['small'] = $simpla->design->resize_modifier($images['filename'], 200, 200, false, false);
                $get_products[$k]['image'][0]['medium'] = $simpla->design->resize_modifier($images['filename'], 500, 500, false, false);
                $get_products[$k]['image'][0]['large'] = $simpla->design->resize_modifier($images['filename'], 800, 800, false, false);
                $get_products[$k]['image'][0]['extra'] = $simpla->design->resize_modifier($images['filename'], 1200, 1200, false, false);
            }
            foreach ($product['images'] as $key_img_images => $images) {
                //  dd($get_products[$k]['images'][$key_img_images]['filename']);
                $get_products[$k]['images'][$key_img_images]['small'] = $simpla->design->resize_modifier($images['filename'], 200, 200, false, false);
                $get_products[$k]['images'][$key_img_images]['medium'] = $simpla->design->resize_modifier($images['filename'], 500, 500, false, false);
                $get_products[$k]['images'][$key_img_images]['large'] = $simpla->design->resize_modifier($images['filename'], 800, 800, false, false);
                $get_products[$k]['images'][$key_img_images]['extra'] = $simpla->design->resize_modifier($images['filename'], 1200, 1200, false, false);
            }
        }
        if ($request->has('limit')) {
            return response()->json($get_products->take($request->limit), 200);
        } else {
            return response()->json($get_products, 200);
        }
        //  return response()->json($get_products, 200);

    }


    public function filterProduct(Request $request)
    {
        // Search for a user based on their name.
        $products = Product::with(['category', 'brands', 'images', 'variants', 'image']);
        // Search for a user based on their company.

        if ($request->has('category_id')) {

            $products->whereHas('category', function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            });

        }

        if ($request->has('brand_id')) {
            $products->where('brand_id', $request->brand_id);
        }

        //recommend products 1 or 0
        if ($request->has('featured')) {
            $products->where('featured', $request->featured);
        }

        if ($request->has('variants')) {
            $products->whereHas('variants', function ($query) use ($request) {
                $query->where('id', $request->variants);
            });
        }

        if ($request->has('sort_by_name')) {

            if ($request->sort_by_name == 'asc') {
                $products->OrderBy('name', 'asc');
            }

            if ($request->sort_by_name == 'desc') {
                $products->OrderBy('name', 'desc');
            }

        }
        if ($request->has('sort_by_price')) {

            if ($request->sort_by_price == 'asc') {
                $products->whereHas('variants', function ($query) use ($request) {
                    $query->OrderBy('price', 'asc');
                });
            }

            if ($request->sort_by_price == 'desc') {
                $products->whereHas('variants', function ($query) use ($request) {
                    $query->OrderBy('price', 'desc');
                });
            }

        }

        if ($request->has('price')) {

            $products->whereHas('variants', function ($query) use ($request) {
                $query->where('price', $request->price);
            });
        }

        //   return $products->with(['category', 'brands', 'images', 'variants', 'image'])->toSql();
        return $products->with(['category', 'brands', 'images', 'variants', 'image'])->get();
    }

    public function getOne($id)
    {

        $simpla = new \Simpla();
        $get_products = Product::with(['category', 'brands', 'images', 'variants', 'image'])->where('id', $id)->get()->toArray();

        $path = $_SERVER['HTTP_HOST'] . '/files/products/';

        foreach ($get_products as $k => $product) {
            foreach ($product['image'] as $key_img => $images) {
                $get_products[$k]['image'][0]['small'] = $simpla->design->resize_modifier($images['filename'], 200, 200, false, false);
                $get_products[$k]['image'][0]['medium'] = $simpla->design->resize_modifier($images['filename'], 500, 500, false, false);
                $get_products[$k]['image'][0]['large'] = $simpla->design->resize_modifier($images['filename'], 800, 800, false, false);
                $get_products[$k]['image'][0]['extra'] = $simpla->design->resize_modifier($images['filename'], 1200, 1200, false, false);
            }
            foreach ($product['images'] as $key_img_images => $images) {
                //  dd($get_products[$k]['images'][$key_img_images]['filename']);
                $get_products[$k]['images'][$key_img_images]['small'] = $simpla->design->resize_modifier($images['filename'], 200, 200, false, false);
                $get_products[$k]['images'][$key_img_images]['medium'] = $simpla->design->resize_modifier($images['filename'], 500, 500, false, false);
                $get_products[$k]['images'][$key_img_images]['large'] = $simpla->design->resize_modifier($images['filename'], 800, 800, false, false);
                $get_products[$k]['images'][$key_img_images]['extra'] = $simpla->design->resize_modifier($images['filename'], 1200, 1200, false, false);
            }
        }

        return response()->json($get_products, 200);

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
        $simpla = new \Simpla();
        $get_products = Product::with(['category', 'brands', 'images', 'variants', 'image'])->where('brand_id', '=', $id)->get()->toArray();

        $path = $_SERVER['HTTP_HOST'] . '/files/products/';

        foreach ($get_products as $k => $product) {
            foreach ($product['image'] as $key_img => $images) {
                $get_products[$k]['image'][0]['small'] = $simpla->design->resize_modifier($images['filename'], 200, 200, false, false);
                $get_products[$k]['image'][0]['medium'] = $simpla->design->resize_modifier($images['filename'], 500, 500, false, false);
                $get_products[$k]['image'][0]['large'] = $simpla->design->resize_modifier($images['filename'], 800, 800, false, false);
                $get_products[$k]['image'][0]['extra'] = $simpla->design->resize_modifier($images['filename'], 1200, 1200, false, false);
            }
            foreach ($product['images'] as $key_img_images => $images) {
                //  dd($get_products[$k]['images'][$key_img_images]['filename']);
                $get_products[$k]['images'][$key_img_images]['small'] = $simpla->design->resize_modifier($images['filename'], 200, 200, false, false);
                $get_products[$k]['images'][$key_img_images]['medium'] = $simpla->design->resize_modifier($images['filename'], 500, 500, false, false);
                $get_products[$k]['images'][$key_img_images]['large'] = $simpla->design->resize_modifier($images['filename'], 800, 800, false, false);
                $get_products[$k]['images'][$key_img_images]['extra'] = $simpla->design->resize_modifier($images['filename'], 1200, 1200, false, false);
            }
        }

        if ($get_products != null) {
            return response()->json($get_products, 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find product with brand id ' . $id], 400);
        }
    }

    public function getCategoryProduct($id)
    {
        $simpla = new \Simpla();
        $get_products = Product::with(['category', 'brands', 'images', 'variants', 'image'])->whereHas('category_id', function ($query) {
            return $query->where('category_id', '=', $id);
        })->get();

        $path = $_SERVER['HTTP_HOST'] . '/files/products/';

        foreach ($get_products as $k => $product) {
            foreach ($product['image'] as $key_img => $images) {
                $get_products[$k]['image'][0]['small'] = $simpla->design->resize_modifier($images['filename'], 200, 200, false, false);
                $get_products[$k]['image'][0]['medium'] = $simpla->design->resize_modifier($images['filename'], 500, 500, false, false);
                $get_products[$k]['image'][0]['large'] = $simpla->design->resize_modifier($images['filename'], 800, 800, false, false);
                $get_products[$k]['image'][0]['extra'] = $simpla->design->resize_modifier($images['filename'], 1200, 1200, false, false);
            }
            foreach ($product['images'] as $key_img_images => $images) {
                //  dd($get_products[$k]['images'][$key_img_images]['filename']);
                $get_products[$k]['images'][$key_img_images]['small'] = $simpla->design->resize_modifier($images['filename'], 200, 200, false, false);
                $get_products[$k]['images'][$key_img_images]['medium'] = $simpla->design->resize_modifier($images['filename'], 500, 500, false, false);
                $get_products[$k]['images'][$key_img_images]['large'] = $simpla->design->resize_modifier($images['filename'], 800, 800, false, false);
                $get_products[$k]['images'][$key_img_images]['extra'] = $simpla->design->resize_modifier($images['filename'], 1200, 1200, false, false);
            }
        }
        return response()->json($get_products);
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
