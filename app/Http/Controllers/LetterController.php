<?php
namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Helpers\UserFriendHelper;
use Illuminate\Http\Request;
use App\Http\Transformers\LetterTransformers;
use App\Letter;


class LetterController extends RestController {
    /**
     * Display a listing of the resource.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $letters  = Letter::where('deleted_at', '=', NULL)->paginate(15);
        return $this->apiPagedSuccess($letters);
    }

    public function timeline($friendUserId)
    {
        $letters  = Letter::timeline_letters($this->thisUserId(),$friendUserId);
        return $this->apiSuccess($letters);
    }

    public function letters($from_user_id)
    {
        $letters  = Letter::letters($from_user_id);
        return $this->apiSuccess($letters);
    }

    /**
     * get a resource with resource id.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $letter  = Letter::with('photos','from_user','to_user','to_user_avatar')->find($id);
        if (!$letter->to_user_id || $letter->to_user_id == 0) {
            if ($letter->from_user_id != $this->thisUserId()) {
                //绑定收件人
                $letter->to_user_id = $this->thisUserId();
                $letter->save();
                UserFriendHelper::addFriend($letter->from_user_id,$letter->to_user_id);
                $letter = Letter::with('photos','from_user','to_user','to_user_avatar')->find($id);

            }
        }

        //TODO should replace with "letter" for "App\Letter" for owner_type of photos.
        if ($letter) {
            return $this->apiSuccess($letter);
        } else {
            return $this->apiError("未找到对应的情书","1001");
        }

    }


    /**
     * Store a newly created resource in storage.
     * @method POST
     */
    public function create(Request $request)
    {
        $rules = [
            'content' => 'required'
        ];
        $this->validate($request, $rules);
        $input = $request->all();
        do {
            $input['security_code'] = "L" . StringHelper::generate_random_code(7);
        } while (count(Letter::where('security_code',$input['security_code'])->first()) > 0);
        $input['qr_code_content'] = md5(microtime(true));
        $input['from_user_id'] = $this->thisUserId();
        $letter = Letter::create($input);
        if ($letter) {
            return $this->apiSuccess($letter);
        } else {
            return $this->apiError("save letter error",1001);
        }
    }

    /**
     * Update the specified resource in storage.
     * @method PUT
     * @param  int $id
     */
    public function update(Request $request, $id)
    {
        $letter  = Letter::find($id);
        $letter->fill($request->all())->save();
        return $this->apiSuccess($letter);
    }

    /**
     * Remove the specified resource from storage.
     * @method DELETE
     * @param  int  $id
     */
    public function destroy($id)
    {
        $letter = Letter::find($id);
        if ($letter->delete()) {
            return $this->apiSuccess($letter);
        }
    }





}