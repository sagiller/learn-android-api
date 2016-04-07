<?php namespace App\Helpers;

use App\UserFriend;
use Illuminate\Support\Facades\DB;
class UserFriendHelper {
    const RESULT_OK = 0;
    const RESULT_ERROR_ALREADY_FRIEND = 1;
    const RESULT_ERROR_WAITING_CONFIRM = 2;
    const RESULT_ERROR_NOT_FRIEND = 3;
    const RESULT_ERROR_DEFAULT = -1;

    public static function addFriend($user_id, $friend_user_id, $comment = null) {
        $map['user_id'] = $user_id;
        $map['friend_user_id'] = $friend_user_id;
        $userFriend = UserFriend::where($map)->first();
        if (!$userFriend) {
            //create friend relationship
            $entity = new UserFriend();
            $entity->user_id = $user_id;
            $entity->friend_user_id = $friend_user_id;
            if ($comment) {
                $entity['comment'] = $comment;
            }
            $entity['status'] = 1; //目前不用等待对方确认，直接添加成功
            $entity->save();

            $entity1 = new UserFriend();
            $entity1->user_id = $friend_user_id;
            $entity1->friend_user_id = $user_id;
            if ($comment) {
                $entity1['comment'] = $comment;
            }
            $entity1['status'] = 1; //目前不用等待对方确认，直接添加成功
            $entity1->save();

            //添加EMchat好友
            $username = DB::table('emchat_user')->select('username')->where('user_id', $user_id)->first()->username;
            $friendUsername = DB::table('emchat_user')->select('username')->where('user_id', $friend_user_id)->first()->username;
            if ($username && $friendUsername) {
                EMChatHelper::addFriend($username,$friendUsername);
            } else {
                //TODO EMChat 用户生成异常处理
            }
            return self::RESULT_OK;
        }

        if ($userFriend->status == 1) {
            return self::RESULT_ERROR_ALREADY_FRIEND;
        } else if ($userFriend->status == 0 ) {
            if ($comment) {
                //update the comment
                $userFriend['comment'] = $comment;
                $userFriend->save();
            }
            return self::RESULT_ERROR_WAITING_CONFIRM;
        }

        return self::RESULT_ERROR_DEFAULT;

    }

    public static function deleteFriend($user_id, $friend_user_id) {
        $map['user_id'] = $user_id;
        $map['friend_user_id'] = $friend_user_id;
        $userFriend = UserFriend::where($map)->first();
        $map1['user_id'] = $friend_user_id;
        $map1['friend_user_id'] = $user_id;
        $userFriend1 = UserFriend::where($map1)->first();
        if (!$userFriend && !$userFriend1) {
            return self::RESULT_ERROR_NOT_FRIEND;
        }
        if($userFriend && $userFriend1) {
            $userFriend->delete();
            $userFriend1->delete();
            return self::RESULT_OK;
        } else {
            return self::RESULT_ERROR_DEFAULT;
        }

    }

    public static function isFriend($user_id, $friend_user_id) {
        $map['user_id'] = $user_id;
        $map['friend_user_id'] = $friend_user_id;
        $userFriend = UserFriend::where($map)->first();
        if ($userFriend && $userFriend->status == 1) {
            return true;
        } else {
            return false;
        }
    }
}