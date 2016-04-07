<?php
/**
 * Created by PhpStorm.
 * User: hujj
 * Date: 2016/2/12
 * Time: 14:48
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class StarDetail extends Model {
    protected $table = 'star_detail';
    protected $fillable = [
        'user_id', 'topic_id','type'
    ];

    public function author()
    {
        return $this->belongsTo('App\User','user_id')->with('author');
    }
}