<?php
/**
 * Created by PhpStorm.
 * User: hujj
 * Date: 2016/2/12
 * Time: 14:48
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class Topic extends Model {
    protected $table = 'topic';
    protected $fillable = [
       'group_id', 'marry_id','user_id','parent_id','relate_id','to_user_id','to_username','title', 'content',
    ];

    /**
     * Get the photos for the topic.
     */
    public function photos()
    {
        return $this->morphMany('App\Image','owner'); //会自动去image表找owner_type和owner_id来匹配
    }
    /**
     * Get the author for the topic.
     */
    public function author()
    {
        return $this->belongsTo('App\User','user_id')->with('avatar');
    }
}