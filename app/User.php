<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract
{
    use Authenticatable, Authorizable;
    public $extend; //一些扩展属性，比如是否与当前oauth的用户是好友

    public function toArray()
    {
        $array = parent::toArray();
        $array['extend'] = $this->extend;
        return $array;
    }


    /**
     * The attributes that are mass assignable.
     * 对于所有Model,都请明确列出所有允许用户传值来修改的字段，未被列出的字段是保护字段，只能通过服务器端代码来修改。
     * @var array
     */
    protected $fillable = [
        'name','email','password','nickname',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the avatar for the user.
     */
    public function avatar()
    {
        return $this->morphOne('App\Image', 'owner');
    }

    /**
     * Get the marry for the user.
     */
    public function marry()
    {
        return $this->hasOne('App\Marry', 'user_id');
    }

    /**
     * relationship
     * Get the emchat_user for the user.
     */
    public function emchat_user()
    {
        return $this->hasOne('App\EMChatUser', 'user_id');
    }

    /**
     * Get the user_friends of the user.
     */
    public function user_friends() {
        return $this->hasMany('App\UserFriend');
    }

    /**
     * Get the friends of the user.
     */
    public function friends()
    {
        $userFriends = $this->user_friends;
        $friends = array();
        foreach($userFriends as $key=>$value) {
            $userFriend = $value;
            $friends[$key] = $userFriend->friend_user;
        }
        return $friends;
    }

    public function timeline_friends()
    {
        $userFriends = $this->user_friends;
        $friends = array();
        $i = 0;
        foreach($userFriends as $key=>$value) {
            $userFriend = $value;
            $friend = $userFriend->friend_user;
            $letters = Letter::timeline_letters($this->id,$friend->id);
            if ($letters->count() > 0) {
                $friends[$i] = $userFriend->friend_user;
                $i++;
            }
        }
        //print_r($friends);exit;
        return $friends;


    }

}
