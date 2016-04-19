<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class WebpageCategory extends Model {
	protected $table = 'webpage_category';

    protected $fillable = [
        'desc','order', 'name', 'type',
    ];


    public function icon()
    {
        return $this->morphOne('App\Image','owner'); //会自动去image表找owner_type和owner_id来匹配
    }

}

?>