<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 's_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['paid','payment_date','closed','email','comment','payment_details','ip','note','coupon_code','delivery_id'];
    public $timestamps = false;
    protected $casts = [
        'payment_date' => 'datetime:Y-m-d',
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
//	protected $hidden = ['password', 'remember_token'];

    public function user()
    {
        return $this->belongsTo('App\Customer', 'user_id');
    }

    public function delivery()
    {
        return $this->belongsTo('App\Delivery', 'delivery_id');
    }

    public function payment_method()
    {
        return $this->belongsTo('App\PaymentMethod', 'payment_method_id');
    }
}
