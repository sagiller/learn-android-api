<?php namespace App\Helpers;

class ResponseHelper {


    public static function apiSuccess($content) {
        return response()->json([
                'message' => 'success',
                'data' => $content,],
            200,
            [
                'Content-Type' => 'application/json;charset=utf-8',
            ], //headers
            JSON_UNESCAPED_UNICODE //don't unicode the 中文
        );
    }

    public static function apiPagedSuccess($content) {
        //TODO 暂时先这么实现踹。。。
        $data = array();
        foreach($content->flatten() as $key=>$item){
            $data[$key] = $item;
        }
        $res['data'] = $data;
        $res['per_page'] = $content->perPage();
        $res['current_page'] = $content->currentPage();
        $res['last_page'] = $content->lastPage();
        $res['next_page_url'] = $content->nextPageUrl();
        $res['prev_page_url'] = $content->previousPageUrl();
        $res['message'] = "success";
        return response()->json(
            $res,
            200,
            [
                'Content-Type' => 'application/json;charset=utf-8',
            ], //headers
            JSON_UNESCAPED_UNICODE //don't unicode the 中文
        );
    }

    public static function apiError($content,$code) {

        return response()->json([
                'message' => 'error',
                'errors' => $content,
                'code' => $code,],
            400,
            [
                'Content-Type' => 'application/json;charset=utf-8',
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    public static function apiException($message,$errors,$httpCode) {

        return response()->json([
                'message' => $message,
                'errors' => $errors,
                'code' => $httpCode,],
            $httpCode,
            [
                'Content-Type' => 'application/json;charset=utf-8',
            ],
            JSON_UNESCAPED_UNICODE
        );
    }
}