<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class EMChatUser extends Model {
    protected $table = 'emchat_user';
    /**
     * The attributes that are mass assignable.
     * 对于所有Model,都请明确列出所有允许用户传值来修改的字段，未被列出的字段是保护字段，只能通过服务器端代码来修改。
     * @var array
     */
    protected $fillable = [
        'nickname','username','password','type','activated','activated','uuid','user_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
