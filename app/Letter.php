<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model {
	protected $table = 'letters';

    protected $fillable = [
        'tag','tag_date','from_user_id', 'to_user_id','content', 'from_nickname', 'to_nickname', 'security_code', 'qr_code_content',
    ];

    /**
     * Get the photos for the letter.
     */
    public function photos()
    {
        return $this->morphMany('App\Image','owner')->where('type', '=', 'letter_photos'); //会自动去image表找owner_type和owner_id来匹配
    }

    /**
     * Get the to user avatar for the letter.
     */
    public function to_user_avatar()
    {
        return $this->morphOne('App\Image','owner')->where('type', '=', 'letter_to_user_avatar'); //会自动去image表找owner_type和owner_id来匹配
    }

    /**
     * Get the from user for the marrywish.
     */
    public function from_user()
    {
        return $this->belongsTo('App\User','from_user_id')->with('avatar');
    }

    /**
     * Get the to user for the marrywish.
     */
    public function to_user()
    {
        return $this->belongsTo('App\User','to_user_id')->with('avatar');
    }

    public static function letters($from_user_id)
    {
        $map['from_user_id'] = $from_user_id;
        return Letter::with('to_user_avatar')->where($map)->orderBy('created_at','desc')->get();
    }

    public static function timeline_letters($from_user_id,$to_user_id)
    {
        $map['from_user_id'] = $from_user_id;
        $map['to_user_id'] = $to_user_id;
        $orMap['from_user_id'] = $to_user_id;
        $orMap['to_user_id'] = $from_user_id;

        return Letter::where($map)->orWhere($orMap)->where('tag','!=','')->whereNotNull('tag')->get();
    }

    public static function is_have_letter($from_user_id)
    {
        $map['from_user_id'] = $from_user_id;
        $letter =  Letter::where($map)->first();
        if ($letter) {
            return true;
        } else {
            return false;
        }
    }
}

?>