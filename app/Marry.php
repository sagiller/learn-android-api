<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Marry extends Model {
	protected $table = 'marry';
    protected $fillable = [
        'user_id', 'groom_user_id', 'bride_user_id','remarks', 'groom_name', 'bride_name', 'qr_code_content', 'security_code',
    ];


    /**
     * Get the couple photo for the marry.
     */
    public function couplePhoto()
    {
        return $this->morphOne('App\Image','owner')->where('type', '=', 'marry_couple'); //会自动去image表找owner_type和owner_id来匹配
    }

    /**
     * Get the photos for the marry.
     */
    public function photos()
    {
        return $this->morphMany('App\Image','owner')->where('type', '=', 'marry_photos'); //会自动去image表找owner_type和owner_id来匹配
    }

    /**
     * Get the marrywishs for the marry.
     */
    public function marryWishs()
    {
        return $this->hasMany('App\Topic','marry_id')->with('photos','author');
    }
}