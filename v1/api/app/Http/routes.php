<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('/home', 'WelcomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

//products
Route::get('/products','ProductsController@getAll');
Route::get('/products/id/{id}','ProductsController@getOne');

Route::get('/products/brand_id/{id}','ProductsController@getBrandProduct');
Route::get('/product/category_id/{id}','ProductsController@getCategoryProduct');

Route::get('/products/featured/{id}','ProductsController@getFeatured');
Route::get('/products/visible/{id}','ProductsController@getVisible');

//Route::get('/products/in_stock/{id}','ProductsController@getById');
//Route::get('/products/has_images/{id}','ProductsController@getById');

Route::get('/product/{id}','ProductsController@getById');
Route::get('/product/url/{url}','ProductsController@getByUrl');

Route::get('/categories','CategoriesController@getAll');
Route::get('/categories/id/{id}','CategoriesController@getById');
Route::get('/category/{id}','CategoriesController@getById');
//Route::get('/category/product_id/{id}','CategoriesController@getByProduct');
//brands
Route::get('/brands','BrandsController@getAll');
Route::get('/brands/id/{id}','BrandsController@getById');
Route::get('/brands/name/{name}','BrandsController@getByName');
Route::get('/brands/url/{url}','BrandsController@getByUrl');

//pages
Route::get('/pages','PagesController@getAll');
Route::get('/page/{id}','PagesController@getById');
//menus
Route::get('/menus','MenusController@getAll');
Route::get('/menu/{id}','MenusController@getById');
//users
Route::get('/users','CustomersController@getAll');
Route::get('/user/{id}','CustomersController@getById');
//resgister
Route::get('/register','CustomersController@getRegister');
//cart
Route::get('/cart','CartController@getAllOrders');
Route::get('/cart/{id}','CartController@getById');
Route::get('/cart/paid/{number}','CartController@getPaid');
Route::get('/cart/closed/{number}','CartController@getClosed');
Route::post('/order','CartController@insertOrder');
//delivery methods
Route::get('/delivery', 'DeliveryController@getAllDeliveryMethods');
Route::get('/delivery/{id}', 'DeliveryController@getDeliveryByID');
Route::get('/paymentmethods', 'PaymentMethodController@getAll');