<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class ProductOptions extends Model  {
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
        return $this->belongsTo('App\Category', 'category');
    }
//    public function features()
//    {
//        return $this->hasMany('App\Features', 'feature_id');
//    }
}
