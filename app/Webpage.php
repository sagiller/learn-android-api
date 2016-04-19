<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Webpage extends Model {
	protected $table = 'webpage';

    protected $fillable = [
        'url','desc','order', 'name','status', 'type',
    ];


    public function icon()
    {
        return $this->morphOne('App\Image','owner'); //会自动去image表找owner_type和owner_id来匹配
    }

    public function category()
    {
        return $this->belongsTo('App\WebPageCategory','category_id');
    }

}

?>