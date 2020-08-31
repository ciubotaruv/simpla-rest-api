<?php

namespace App\Http\Controllers;
use App\Page;
use App\Menu;

class PagesController extends Controller {

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
        $get_products = Page::all()->toArray();
        return response()->json($get_products);

	//	return view('welcome');
	}

	public function getByID($id)
    {
        $get_products = Page::with('menus')->where('id','=',$id)->get();
        return response()->json($get_products);
    }

}
