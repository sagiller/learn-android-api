<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Letter,App\Marry;


class OpenLoveController extends RestController {

    /**
     * open a letter or get a marry by qr_code_content and security_code
     *
     * @method PUT
     * @param  Request  $request
     */
    public function open(Request $request)
    {
        if ($request->get('qr_code_content')) {
            $letter = Letter::where('qr_code_content',$request->get('qr_code_content'))->first();
            $marry = Marry::where('qr_code_content',$request->get('qr_code_content'))->first();

        } else {
            $letter = Letter::where('security_code',$request->get('security_code'))->first();
            $marry = Marry::where('security_code',$request->get('security_code'))->first();
        }
        if ($letter) {
            $love['id'] = $letter->id;
            $love['type'] = "letter";
            return $this->apiSuccess($love);
        } else if ($marry) {
            $love['id'] = $marry->id;
            $love['type'] = "marry";
            return $this->apiSuccess($love);
        } else {
            return $this->apiError("并没有找到情书或婚庆",1001);
        }
    }
}