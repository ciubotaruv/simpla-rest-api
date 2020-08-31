<?php

namespace App\Http\Controllers;
use App\Customer;
use App\Menu;

class CustomersController extends Controller {

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
        $get_products = Customer::with('group')->get();
        return response()->json($get_products);

	//	return view('welcome');
	}

	public function getByID($id)
    {
        $get_products = Customer::with('group')->where('id','=',$id)->get();
        return response()->json($get_products);
    }
    public function getRegister()
    {
        $get_products ='url';
        return response()->json($get_products);
    }
}
