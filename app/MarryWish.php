<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class MarryWish extends Model {
	protected $table = 'marry_wish';
    protected $fillable = [
        'user_id', 'marry_id','content', 'from_name',
    ];

    /**
     * Get the photos for the marrywish.
     */
    public function photos()
    {
        return $this->morphMany('App\Image','owner'); //会自动去image表找owner_type和owner_id来匹配
    }
    /**
     * Get the author for the marrywish.
     */
    public function author()
    {
        return $this->belongsTo('App\User','user_id')->with('avatar');
    }
}