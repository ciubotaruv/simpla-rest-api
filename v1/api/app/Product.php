<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 's_products';

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
        return $this->hasMany('App\ProductCategory', 'product_id');
    }
    public function options()
    {
        return $this->hasMany('App\Options', 'product_id')->with('features');
    }
    public function images()
    {
        return $this->hasMany('App\Image', 'product_id');
    }
    public function image()
    {
        return $this->hasMany('App\Image', 'product_id')->where('position', 0);
    }
    public function related()
    {
        return $this->hasMany('App\RelatedProducts', 'product_id')->with('product');
    }
    public function variants()
    {
        return $this->hasMany('App\Variant', 'product_id');
    }

    public function brands()
    {
        return $this->belongsTo('App\Brand', 'brand_id');
    }

    public function scopePopular($query, $id)
    {
        if ($id != null) {
            return $query->where('brand_id', '=', $id);
        }
    }

    public function scopeVisible($query, $id)
    {
        if ($id != null) {
            return $query->where('id', '=', $id);
        }
    }

    public function scopeOne($query, $id)
    {
             return  $query->where('brands.product_id', '=', $id);
    }

    public function filter_one($query, $o1, $o2,$o3)
    {
        $query->where('brand_id', '=', 1);
         $query->where('visible', '=', 1);
         $query->where('position', '=', 38);
        return $query;
    }

    public function filter23(Product $user)
    {
        // Search for a user based on their name.
        if ($user) {
            return $user->where('brand_id', 1)->get();
        }

        // Search for a user based on their company.
        if ($user) {
            return $user->where('id', 1)
                ->get();
        }

        // Continue for all of the filters.

        // No filters have been provided, so
        // let's return all users. This is
        // bad - we should paginate in
        // reality.
        return Product::all();
    }
}
