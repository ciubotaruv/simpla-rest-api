<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Page extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 's_pages';

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

    public function menus()
    {
        return $this->belongsTo('App\Menu', 'menu_id');
    }

}
