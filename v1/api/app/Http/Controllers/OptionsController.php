<?php

namespace App\Http\Controllers;

use App\Options;

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
    public function getAll()
    {
        $get_products = Options::all()->toArray();
        //$get_products = Brand::lists('name');
        return response()->json($get_products);

        //	return view('welcome');
    }

    public function getById($id)
    {
        $get_products = Options::where('id', '=', $id)->get();
        return response()->json($get_products);
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
