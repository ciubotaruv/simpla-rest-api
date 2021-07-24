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
        $get_products = Product::with(['category', 'brands', 'images', 'variants', 'image', 'options', 'related', 'comments'])->get();
        $path = $_SERVER['HTTP_HOST'] . '/files/products/';

        foreach ($get_products as $k => $product) {
            foreach ($product['related'] as $key_img => $images) {
                foreach ($get_products[$k]['related'][$key_img]['product']['image'] as  $kd => $img) {

                    $get_products[$k]['related'][$key_img]['product']['image'][0]['small'] = $simpla->design->resize_modifier($img['filename'], 200, 200, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['medium'] = $simpla->design->resize_modifier($img['filename'], 500, 500, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['large'] = $simpla->design->resize_modifier($img['filename'], 800, 800, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['extra'] = $simpla->design->resize_modifier($img['filename'], 1200, 1200, false, false);
                }

                foreach ( $get_products[$k]['related'][$key_img]['product']['images'] as $kl => $img2) {
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['small'] = $simpla->design->resize_modifier($img2['filename'], 200, 200, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['medium'] = $simpla->design->resize_modifier($img2['filename'], 500, 500, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['large'] = $simpla->design->resize_modifier($img2['filename'], 800, 800, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['extra'] = $simpla->design->resize_modifier($img2['filename'], 1200, 1200, false, false);
                }
            }
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
            $product['related'] = $product['image'];
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
        parse_str($_SERVER['QUERY_STRING'], $get_array);
        $all_feature_id = [];
        $all_feature_value = [];

        foreach ($get_array as $k => $item) {
            if (gettype($k) == 'integer') {
                $all_feature_id[] = $k;
                $all_feature_value[] = $item;
            }
        }


        //  dd($_SERVER['QUERY_STRING']);
        // Search for a user based on their name.
        $products = Product::with(['category', 'brands', 'images', 'variants', 'image', 'options', 'related']);
        // Search for a user based on their company.

        if ($request->has('category_id')) {
            $products->whereHas('category', function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            });

        }
        if ($all_feature_id != null && $all_feature_value != null) {
            $products->whereHas('options', function ($query) use ($all_feature_value, $all_feature_id) {
                $query->whereIn('feature_id', $all_feature_id)->whereIn('value', $all_feature_value);
            });
        }

        if ($request->has('brand_id')) {
            $brands_id = explode(',', $request->brand_id);
            $products->whereIn('brand_id', $brands_id);
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

        $get_products = $products->with(['category', 'brands', 'images', 'variants', 'image'])->get();
        $simpla = new \Simpla();

        foreach ($get_products as $k => $product) {
            foreach ($product['related'] as $key_img => $images) {
                foreach ($get_products[$k]['related'][$key_img]['product']['image'] as  $kd => $img) {

                    $get_products[$k]['related'][$key_img]['product']['image'][0]['small'] = $simpla->design->resize_modifier($img['filename'], 200, 200, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['medium'] = $simpla->design->resize_modifier($img['filename'], 500, 500, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['large'] = $simpla->design->resize_modifier($img['filename'], 800, 800, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['extra'] = $simpla->design->resize_modifier($img['filename'], 1200, 1200, false, false);
                }

                foreach ( $get_products[$k]['related'][$key_img]['product']['images'] as $kl => $img2) {
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['small'] = $simpla->design->resize_modifier($img2['filename'], 200, 200, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['medium'] = $simpla->design->resize_modifier($img2['filename'], 500, 500, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['large'] = $simpla->design->resize_modifier($img2['filename'], 800, 800, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['extra'] = $simpla->design->resize_modifier($img2['filename'], 1200, 1200, false, false);
                }
            }
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
            $limit = explode(',', $request->limit);
            $skip = $limit[0];
            $take = isset($limit[1]);

            if (!$take) {
                return response()->json($get_products->take($skip), 200);
            } else {
                $take = $limit[1];
                return response()->json($get_products->slice($skip)->take($take), 200);
            }
        } else {
            return response()->json($get_products, 200);
        }
        // return response()->json($get_products->slice(4)->take(1), 200);
    }

    public function countProduct(Request $request)
    {
        parse_str($_SERVER['QUERY_STRING'], $get_array);
        $all_feature_id = [];
        $all_feature_value = [];

        foreach ($get_array as $k => $item) {
            if (gettype($k) == 'integer') {
                $all_feature_id[] = $k;
                $all_feature_value[] = $item;
            }
        }

        // Search for a user based on their name.
        $products = Product::with(['category', 'brands', 'images', 'variants', 'image', 'options', 'related']);
        // Search for a user based on their company.

        if ($request->has('category_id')) {
            $products->whereHas('category', function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            });

        }

        if ($all_feature_id != null && $all_feature_value != null) {
            $products->whereHas('options', function ($query) use ($all_feature_value, $all_feature_id) {
                $query->whereIn('feature_id', $all_feature_id)->whereIn('value', $all_feature_value);
            });
        }

        if ($request->has('name')) {
            $options_name = explode(',', $request->name);
            $products->whereHas('options', function ($query) use ($options_name) {
                $query->whereIn('value', $options_name);
            });

        }

        if ($request->has('brand_id')) {
            $brands_id = explode(',', $request->brand_id);
            $products->whereIn('brand_id', $brands_id);
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
        $get_products = $products->with(['category', 'brands', 'images', 'variants', 'image'])->get();
        $simpla = new \Simpla();


        foreach ($get_products as $k => $product) {
            foreach ($product['related'] as $key_img => $images) {
                foreach ($get_products[$k]['related'][$key_img]['product']['image'] as  $kd => $img) {

                    $get_products[$k]['related'][$key_img]['product']['image'][0]['small'] = $simpla->design->resize_modifier($img['filename'], 200, 200, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['medium'] = $simpla->design->resize_modifier($img['filename'], 500, 500, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['large'] = $simpla->design->resize_modifier($img['filename'], 800, 800, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['extra'] = $simpla->design->resize_modifier($img['filename'], 1200, 1200, false, false);
                }

                foreach ( $get_products[$k]['related'][$key_img]['product']['images'] as $kl => $img2) {
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['small'] = $simpla->design->resize_modifier($img2['filename'], 200, 200, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['medium'] = $simpla->design->resize_modifier($img2['filename'], 500, 500, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['large'] = $simpla->design->resize_modifier($img2['filename'], 800, 800, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['extra'] = $simpla->design->resize_modifier($img2['filename'], 1200, 1200, false, false);
                }
            }
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
            $limit = explode(',', $request->limit);
            $skip = $limit[0];
            $take = isset($limit[1]);

            if (!$take) {
                $count = $get_products->take($skip);
                return response()->json(count($count), 200);
            } else {
                $take = $limit[1];
                $count = $get_products->slice($skip)->take($take);
                return response()->json(count($count), 200);
            }
        } else {
            return response()->json(count($get_products), 200);
        }
    }


    public function getOne($id)
    {

        $simpla = new \Simpla();
        $get_products = Product::with(['category', 'brands', 'images', 'variants', 'image', 'options', 'related'])->where('id', $id)->get()->toArray();

        $path = $_SERVER['HTTP_HOST'] . '/files/products/';

        foreach ($get_products as $k => $product) {
            foreach ($product['related'] as $key_img => $images) {
                foreach ($get_products[$k]['related'][$key_img]['product']['image'] as  $kd => $img) {

                    $get_products[$k]['related'][$key_img]['product']['image'][0]['small'] = $simpla->design->resize_modifier($img['filename'], 200, 200, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['medium'] = $simpla->design->resize_modifier($img['filename'], 500, 500, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['large'] = $simpla->design->resize_modifier($img['filename'], 800, 800, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['extra'] = $simpla->design->resize_modifier($img['filename'], 1200, 1200, false, false);
                }

                foreach ( $get_products[$k]['related'][$key_img]['product']['images'] as $kl => $img2) {
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['small'] = $simpla->design->resize_modifier($img2['filename'], 200, 200, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['medium'] = $simpla->design->resize_modifier($img2['filename'], 500, 500, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['large'] = $simpla->design->resize_modifier($img2['filename'], 800, 800, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['extra'] = $simpla->design->resize_modifier($img2['filename'], 1200, 1200, false, false);
                }
            }
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
            $product['related'] = $product['image'];
        }

        return response()->json($get_products, 200);

    }

    public function getByID($id)
    {
        $product = Product::with(['category', 'brands', 'images', 'variants', 'image', 'options', 'related'])->where('id', '=', $id)->get()->toArray();

        if ($product != null) {
            return response()->json($product, 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find product with ID ' . $id], 400);
        }


    }

    public function getByUrl($url)
    {
        $product = Product::with(['category', 'brands', 'images', 'variants', 'image', 'options', 'related'])->where('url', '=', $url)->get()->toArray();

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
        $get_products = Product::with(['category', 'brands', 'images', 'variants', 'image', 'options', 'related'])->where('brand_id', '=', $id)->get()->toArray();

        $path = $_SERVER['HTTP_HOST'] . '/files/products/';

        foreach ($get_products as $k => $product) {
            foreach ($product['related'] as $key_img => $images) {
                foreach ($get_products[$k]['related'][$key_img]['product']['image'] as  $kd => $img) {

                    $get_products[$k]['related'][$key_img]['product']['image'][0]['small'] = $simpla->design->resize_modifier($img['filename'], 200, 200, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['medium'] = $simpla->design->resize_modifier($img['filename'], 500, 500, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['large'] = $simpla->design->resize_modifier($img['filename'], 800, 800, false, false);
                    $get_products[$k]['related'][$key_img]['product']['image'][0]['extra'] = $simpla->design->resize_modifier($img['filename'], 1200, 1200, false, false);
                }

                foreach ( $get_products[$k]['related'][$key_img]['product']['images'] as $kl => $img2) {
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['small'] = $simpla->design->resize_modifier($img2['filename'], 200, 200, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['medium'] = $simpla->design->resize_modifier($img2['filename'], 500, 500, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['large'] = $simpla->design->resize_modifier($img2['filename'], 800, 800, false, false);
                    $get_products[$k]['related'][$key_img]['product']['images'][$kl]['extra'] = $simpla->design->resize_modifier($img2['filename'], 1200, 1200, false, false);
                }
            }
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
            $product['related'] = $product['image'];
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
        $get_products = Product::with(['category', 'brands', 'images', 'variants', 'image', 'options', 'related'])->whereHas('category_id', function ($query) {
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
        $get_featured = Product::with(['category', 'brands', 'images', 'variants', 'image', 'options', 'related'])->where('featured', '=', $id)->get();
        return response()->json($get_featured);
    }

    public function getVisible($id)
    {
        $get_visible = Product::with(['category', 'brands', 'images', 'variants', 'image', 'options', 'related'])->where('visible', '=', $id)->get();
        return response()->json($get_visible);
    }

    public function search(Request $request)
    {

        $search = $request->search;
        // Search for a user based on their name.
        $get_products = Product::with(['category', 'brands' => function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }, 'images', 'variants', 'image', 'options', 'related'])
            ->where("name", "like", '%' . $search . '%')
//            ->whereHas('brands', function($q) use ($search) {
//                $q->where("name", "like", $search);
//                    })
            ->get()
            ->toArray();


        //   return $products->with(['category', 'brands', 'images', 'variants', 'image'])->toSql();
        //$get_products = $products->with(['category', 'brands', 'images', 'variants', 'image'])->get();
        $simpla = new \Simpla();
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
}
