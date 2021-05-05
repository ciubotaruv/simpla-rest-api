<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class RelatedProducts extends Model  {
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 's_related_products';

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
    public function product()
    {
        return $this->belongsTo('App\Product', 'related_id');
    }
}
