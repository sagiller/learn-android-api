<?php namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Cache;
use App\EMChatUser;
class EMChatHelper {
    /**
     * @param $username
     * @param $password
     * @param $nickname
     * @return mixed
     */
    public static function register($userId,$username,$password,$nickname = null) {
        $client = self::initHttpClient();
        $body['username'] = $username;
        $body['password'] = $password;
        if ($nickname) {
            $body['nickname'] = $nickname;
        }
        $request = self::buildPostRequest("users",$body);
        $response = $client->send($request, ['timeout' => 4]);
        if ($response->getStatusCode() == 200) {
            $res = json_decode($response->getBody(),false);
            $entity =$res->entities[0];
            $user['user_id'] = $userId;
            $user['uuid'] = $entity->uuid;
            $user['username'] = $username;
            $user['password'] = $password;
            $user['type'] = $entity->type;
            $user['activated'] = $entity->activated;
            EMChatUser::create($user);
            return $user;
        }
    }

    public static function sendMessage($target,$msg,$from = null,$targetType = "users",$ext = null) {
        $client = self::initHttpClient();
        $body['target_type'] = $targetType;
        $body['target'] = $target;
        $body['msg']['type'] = "txt";
        $body['msg']['msg'] = $msg;

        if ($from) {
            $body['from'] = $from;
        }
        $body['target'] = $target;
        if ($ext) {
            $body['ext'] = $ext;
        }
        $request = self::buildPostRequest("messages",$body);
        $response = $client->send($request, ['timeout' => 4]);
        if ($response->getStatusCode() == 200) {
            $resBody = json_decode($response->getBody(),false);
            return $resBody->data;
        } else {
            return null;
        }
    }

    public static function addFriend($username,$friend_user_name) {
        $client = self::initHttpClient();
        $subUrl = "users/".$username."/contacts/users/".$friend_user_name;
        $request = self::buildPostRequest($subUrl,null);
        $response = $client->send($request, ['timeout' => 4]);
        if ($response->getStatusCode() == 200) {
            $resBody = json_decode($response->getBody(),false);
            return $resBody;
        } else {
            return null;
        }
    }


    public static function getToken() {
        if (!self::tokenExpired()) {
            return Cache::get('emchat_access_token');
        }
        $client = self::initHttpClient();
        $body['grant_type'] = "client_credentials";
        $body['client_id'] = env('EMCHAT_CLIENT_ID');
        $body['client_secret'] = env("EMCHAT_CLIENT_SECRET");
        //return json_encode($body);
        $request = self::buildPostRequest("token",$body,false);
        $response = $client->send($request, ['timeout' => 4]);

        if ($response->getStatusCode() == 200) {
            $res = json_decode($response->getBody(),true);
            Cache::store('redis')->put('emchat_token_create_time',time(),10);
            Cache::store('redis')->put('emchat_access_token',$res['access_token'],10);
            Cache::store('redis')->put('emchat_token_expires_in',$res['expires_in'],10);
            Cache::store('redis')->put('emchat_token_application',$res['application'],10);
            return $res['access_token'];
        }
    }

    public static function tokenExpired() {
        $createdTime = Cache::get('emchat_token_create_time');
        $expiresIn = Cache::get('emchat_token_expires_in');
        if (!$createdTime || !$expiresIn) {
            return true;
        }
        if (time() - $createdTime  > ($expiresIn - 10000)) {
            $accessToken = Cache::get('emchat_access_token');
            if (!$accessToken || $accessToken == '') {
                Cache::store('redis')->put('emchat_token_create_time',null);
                Cache::store('redis')->put('emchat_access_token',null);
                Cache::store('redis')->put('emchat_token_expires_in',null);
                Cache::store('redis')->put('emchat_token_application',null);
                return true; //过期了
            }
            return true; //过期了
        } else {
            return false; //没有过期
        }
    }


    public static function initHttpClient() {
        $client = new Client([
                // Base URI is used with relative requests
            'base_uri' => env('EMCHAT_BASEURL') . env('EMCHAT_APPKEY') . "/",
                // You can set any number of default request options.
                'timeout'  => 2.0,
            ]);
        return $client;
    }

    public static function buildGetRequest($subUrl,$body,$withAuth = true){
        $headers['Content-Type'] = "application/json";
        $headers['Accept'] = "application/json";
        if ($withAuth) {
            $headers['Authorization'] = "Bearer ".self::getToken();
        }
        $request = new Request('GET', $subUrl,$headers,json_encode($body));
        return $request;
    }

    public static function buildPostRequest($subUrl,$body,$withAuth = true){
        $headers['Content-Type'] = "application/json";
        $headers['Accept'] = "application/json";
        if ($withAuth) {
            $headers['Authorization'] = "Bearer ".self::getToken();
        }
        $request = new Request('POST', $subUrl,$headers,json_encode($body));
        return $request;
    }


}