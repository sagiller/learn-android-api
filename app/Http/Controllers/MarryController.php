<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Marry;
use App\Helpers\StringHelper;


class MarryController extends RestController {
    /**
     * Display a listing of the resource.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marries = Marry::with('couplePhoto','photos')->paginate(15);
        return $this->apiPagedSuccess($marries);
    }

    /**
     * get a resource with resource id.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marry = Marry::with('couplePhoto','photos','marryWishs')->find($id);
        if ($marry) {
            return $this->apiSuccess($marry);
        } else {
            return $this->apiError("未找到对应的婚庆数据","1001");
        }

    }

    /**
     * get a resource with resource qrcodecontent.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function showByQrCodeContent($qrCodeContent)
    {
        $marry = Marry::with('couplePhoto','photos','marryWishs')->where('qr_code_content',$qrCodeContent)->first();
        if ($marry) {
            return $this->apiSuccess($marry);
        } else {
            return $this->apiError("未找到对应的婚庆数据","1001");
        }

    }


    /**
     * Store a newly created resource in storage.
     * @method POST
     */
    public function create(Request $request)
    {
        $rules = [
            'remarks' => 'required',
            'groom_name' => 'required',
            'bride_name' => 'required'
        ];
        $this->validate($request, $rules);
        $input = $request->all();
        $input['user_id'] = $this->thisUserId();
        do {
            $input['security_code'] = "M" . StringHelper::generate_random_code(7);
        } while (count(Marry::where('security_code',$input['security_code'])->first()) > 0);
        $input['qr_code_content'] = md5(microtime(true));
        $marry = Marry::create($input);
        if ($marry) {
            return $this->apiSuccess($marry);
        } else {
            return $this->apiError("save marry wish error",1001);
        }
    }



    /**
     * Update the specified resource in storage.
     * @method PUT
     * @param  int $id
     */
    public function update(Request $request, $id)
    {
        $marry  = Marry::find($id);
        $marry->fill($request->all())->save();
        return $this->apiSuccess($marry);
    }

    /**
     * Remove the specified resource from storage.
     * @method DELETE
     * @param  int  $id
     */
    public function destroy($id)
    {
        $marry = Marry::find($id);
        if ($marry->delete()) {
            return $this->apiSuccess($marry);
        }
    }





}