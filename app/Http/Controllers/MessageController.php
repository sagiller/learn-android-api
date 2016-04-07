<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\EMChatHelper;
use Illuminate\Support\Facades\DB;


class MessageController extends RestController {

    /**
     * send a message to single target
     *
     * @method POST
     * @param  Request  $request
     */
    public function send(Request $request)
    {
        $rules = [
            'target' => 'required',
            'msg' => 'required',
        ];
        $this->validate($request, $rules);
        $user = $this->thisUser();
        $msg = $request->get("msg");
        $usernames = DB::table('emchat_user')->select('username')->whereIn('user_id', $request->get("target"))->get();
        if (count($usernames) < 1) {
            return $this->apiError("invalid target ids",1001);
        }
        foreach ($usernames as $key => $value) {
            $target[$key] = $value->username;
        }
        $res =  EMChatHelper::sendMessage($target,$msg,$user->emchat_user->username);
        if($res) {
            return $this->apiSuccess($res);
        }

    }
}