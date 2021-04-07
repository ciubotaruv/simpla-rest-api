<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Options extends Model  {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 's_options';

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
	protected $hidden = ['product_id'];
    public function features()
    {
        return $this->belongsTo('App\Features', 'feature_id');
    }
}
