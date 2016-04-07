<?php
namespace App\Http\Controllers;

use App\Helpers\UserFriendHelper;
use Illuminate\Http\Request;
use App\UserFriend,App\User;


class UserFriendController extends RestController {
    public function index() {
        $user = User::find($this->thisUserId());
        return $this->apiSuccess($user->friends());
    }

    public function timeline() {
        $user = User::find($this->thisUserId());
        return $this->apiSuccess($user->timeline_friends());
    }



    /**
     * create friend relationship
     *
     * @method POST
     * @param  Request  $request
     * @sample_url http://192.168.1.103:8000/api/v1/userfriend
     * @sample_body {"friend_user_id":"2","comment":"加我"}
     */
    public function create(Request $request)
    {
        $rules = [
            'friend_user_id' => 'required',
            //'comment' optional
        ];
        $this->validate($request, $rules);
        $friendUserId = $request->get('friend_user_id');
        if ($this->thisUserId() == $friendUserId) {
            return $this->apiError("您不能添加自己为好友",-1);
        }
        $res = UserFriendHelper::addFriend($this->thisUserId(),$friendUserId,$request->get('comment'));
        if (is_int($res)) {
            switch($res) {
                case UserFriendHelper::RESULT_OK:
                    $user = User::with('avatar','emchat_user')->find($friendUserId);
                    if ($user) {
                        $extend['is_friend_with_oauth_user'] = UserFriendHelper::isFriend($this->thisUserId(), $friendUserId);
                        $user->extend = $extend;
                        return $this->apiSuccess($user);
                    }
                    break;
                case UserFriendHelper::RESULT_ERROR_ALREADY_FRIEND:
                    return $this->apiError("已经是好友了",1001);
                    break;
                case UserFriendHelper::RESULT_ERROR_ALREADY_FRIEND:
                    return $this->apiError("添加好友出错",-1);
                    break;
                case UserFriendHelper::RESULT_ERROR_WAITING_CONFIRM:
                    return $this->apiError("等待对方通过中",1002);
                default:
                    return $this->apiError("未知错误:".$res,-1);
                    break;
            }
        } else {
           return $this->apiError("未知错误:".$res,-1);
        }

    }

    public function delete($userId,$friendUserId)
    {
        $rules = [
            'friend_user_id' => 'required',
            //'comment' optional
        ];
        $res = UserFriendHelper::deleteFriend($this->thisUserId(),$friendUserId);
        if (is_int($res)) {
            switch($res) {
                case UserFriendHelper::RESULT_OK:
                    $user = User::with('avatar','emchat_user')->find($friendUserId);
                    if ($user) {
                        $extend['is_friend_with_oauth_user'] = UserFriendHelper::isFriend($this->thisUserId(), $friendUserId);
                        $user->extend = $extend;
                        return $this->apiSuccess($user);
                    }
                    break;
                case UserFriendHelper::RESULT_ERROR_NOT_FRIEND:
                    return $this->apiError("还不是好友，不能删除好友",1001);
                    break;
                default:
                    return $this->apiError("未知错误:".$res,-1);
                    break;
            }
        } else {
            return $this->apiError("未知错误:".$res,-1);
        }

    }


}