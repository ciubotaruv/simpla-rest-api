<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class ProductCategory extends Model  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 's_products_categories';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
//	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
//	protected $hidden = ['password', 'remember_token'];
    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }
    public function products()
    {
        return $this->belongsTo('App\Product', 'product_id')->with(['brands', 'images', 'variants', 'image','options','related']);
    }

}
