<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class UserFriend extends Model {
	protected $table = 'user_friend';
    protected $fillable = [
        'user_id', 'friend_user_id', 'comment','status',
    ];

    /**
     * Get the owner user for the user.
     */
    public function owner_user()
    {
        return $this->belongsTo('App\User', 'user_id')->with('avatar','emchat_user');
    }

    /**
     * Get the friend user for the user.
     */
    public function friend_user()
    {
        return $this->belongsTo('App\User', 'friend_user_id')->with('avatar','emchat_user');
    }
}