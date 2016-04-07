<?php

namespace App\Http\Controllers;
use AlidayuUtil;
use App\Helpers\EMChatHelper;
use App\Helpers\StringHelper;
use App\Helpers\UserFriendHelper;
use App\Letter;
use App\User;
use App\UserFriend;
use HttpdnsGetRequest;
use Illuminate\Http\Request;
use App\Sms;

class UserController extends RestController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * temp login
     *
     * @method POST
     * @param  Request  $request
     */
    public function login(Request $request)
    {
        $rules = [
            'phone' => 'required',
            'password' => 'required|min:6'
        ];
        $this->validate($request, $rules);
        $user = User::with('avatar','emchat_user','marry')->where('phone',$request->get('phone'))->first();
        if ($user) {
            if ($user['password'] == $request->get('password')) {
                $extend['is_have_letter'] = Letter::is_have_letter($user->id);
                $user->extend = $extend;
                return $this->apiSuccess($user);
            } else {
                return $this->apiError("您的密码不对哦",1002);
            }
        } else {
            return $this->apiError("并没有找到这个用户",1001);
        }
    }

    /**
     * temp login
     *
     * @method POST
     * @param  Request  $request
     */
    public function register(Request $request)
    {
        $rules = [
            'phone' => 'required',
            'password' => 'required|min:6',
            'code' => 'required|min:4'
        ];
        $this->validate($request, $rules);
        $user = User::where('phone',$request->get('phone'))->first();
        if ($user) {
            return $this->apiError("该手机号已被注册","1001");
        }
        $map['phone'] = $request->get('phone');
        $map['type'] = 1;
        $sms = Sms::where($map)->first();

        if (!$sms || $sms->code!=$request->get('code')) {
           return $this->apiError("验证码校验失败","1002");
        }

        $user = new User();
        $user->phone = $request->get('phone');
        $user->password = $request->get('password');
        $user->api_token = $this->generate_api_token();
        if ($user->save()) {
            $username = StringHelper::generate_random_code(6).$user->phone;
            $password = StringHelper::generate_random_code(10);
            EMChatHelper::register($user->id,$username,$password);
            return $this->apiSuccess(User::with('avatar','emchat_user')->find($user->id));
        }
        return $this->apiError("未知错误","1003");

    }

    /**
     * update
     *
     * @method PUT
     * @param  Request  $request
     */
    public function update(Request $request)
    {
        $user = User::find($this->thisUserId());
        if ($request->get('name')) {
            $user->name = $request->get('name');
        }
        if ($request->get('nickname')) {
            $user->nickname = $request->get('nickname');
        }
        if ($user->save()) {
            //update nickname to emchat
            return $this->apiSuccess(User::with('avatar','emchat_user','marry')->find($user->id));
        }
        return $this->apiError("未知错误","1003");

    }

    /**
     * update
     *
     * @method PUT
     * @param  Request  $request
     */
    public function updatePassword(Request $request)
    {
        $rules = [
            'old_password' => 'required',
            'password' => 'required|min:6'
        ];
        $this->validate($request, $rules);
        $user = User::find($this->thisUserId());
        if ($request->get('old_password') != $user->password) {
            return $this->apiError("旧密码不符","1003");
        }

        if ($request->get('old_password') == $request->get('password')) {
            return $this->apiError("新旧密码不能相同","1003");
        }
        $user->password = $request->get('password');
        if ($user->save()) {
            return $this->apiSuccess("");
        }
        return $this->apiError("未知错误","1003");

    }

    /**
     * temp getRegisterSms
     *
     * @method POST
     * @param  Request  $request
     */
    public function getRegisterSms(Request $request)
    {
        $rules = [
            'phone' => 'required',
            'type' => 'required'
        ];
        $this->validate($request, $rules);
        $util = new AlidayuUtil;
        $code = $util->generate_code();

        $res = $util->send($request->get('phone'),$code,$request->get('type'));
        if ($util->isSuccess(json_encode($res)) == "true") {
            $map['phone'] = $request->get('phone');
            $map['type'] = $request->get('type');

            $sms = Sms::where($map)->first();
            if ($sms) {
                $sms->code = $code;
                $sms->save();
            } else {
                $sms = new Sms();
                $sms->code = $code;
                $sms->phone = $request->get('phone');
                $sms->type = $request->get('type');
                $sms->save();
            }
            return $this->apiSuccess($sms);
        } else {
            return $this->apiError("send msg fail","1001");
        }

    }

    /**
     * get a resource with resource id.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('avatar','emchat_user')->find($id);
        if ($user) {
            $extend['is_friend_with_oauth_user'] = UserFriendHelper::isFriend($this->thisUserId(),$id);
            $user->extend = $extend;
            return $this->apiSuccess($user);
        } else {
            return $this->apiError("未找到该用户","1001");
        }

    }

    function generate_api_token() {
        return md5(microtime(true));
    }


}
