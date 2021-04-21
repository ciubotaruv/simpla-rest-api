<?php

namespace App\Http\Controllers;

use App\Options;
use Illuminate\Http\Request;

class OptionsController extends Controller
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
        $get_products = Options::query();

        if ($request->has('feature_id')) {
            $get_products->where('feature_id', $request->feature_id);
        }

        if ($get_products != null) {
            return response()->json($get_products->get(), 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find this query' . $get_products], 400);
        }
    }

    public function getById(Request $request)
    {
        $get_products = Options::query();

        if ($request->has('feature_id')) {
            $get_products->where('feature_id', $request->feature_id);
        }

        if ($get_products != null) {
            return response()->json($get_products->get(), 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find this query' . $get_products], 400);
        }

    }

    public function getByName($name)
    {

        $Brand = Options::all()->where('value', $name)->toArray();
        if ($Brand != null) {
            return response()->json($Brand, 200);
        } else {
            return response()->json(['error' => 1, 'message' => 'Unable to find options with name ' . $name], 400);
        }

    }


}
