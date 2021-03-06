<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 's_categories';

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
    public function products()
    {
        return $this->hasMany('App\Product', 'product_id');
    }

//    public function features()
//    {
//        //return $this->hasMany('App\CategoriesFetures', 'product_id');
//        return $this->hasMany('App\CategoriesFetures','category_id');
//    }
}
