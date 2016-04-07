<?php
namespace App\Http\Controllers;

use App\MarryWish;
use Illuminate\Http\Request;


class MarryWishController extends RestController {
    /**
     * Display a listing of the resource.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marries = MarryWish::paginate(15);
        return $this->apiPagedSuccess($marries);
    }

    public function indexByMarryId($marryId)
    {
        $marries = MarryWish::with('author')->where('marry_id',$marryId)->paginate(15);
        return $this->apiPagedSuccess($marries);
    }
    /**
     * get a resource with resource id.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marryWish = MarryWish::find($id);
        if ($marryWish) {
            return $this->apiSuccess($marryWish);
        } else {
            return $this->apiError("未找到对应的婚庆wish数据","1001");
        }

    }

    /**
     * Store a newly created resource in storage.
     * @method POST
     */
    public function create(Request $request)
    {
        $rules = [
            'marry_id' => 'required',
            'content' => 'required',
            'from_name' => 'required'
        ];
        $this->validate($request, $rules);
        $input = $request->all();
        $input['user_id'] = $this->thisUserId();
        $marryWish = MarryWish::create($input);
        if ($marryWish) {
            return $this->apiSuccess($marryWish);
        } else {
            return $this->apiError("save marrywish wish error",1001);
        }
    }



    /**
     * Update the specified resource in storage.
     * @method PUT
     * @param  int $id
     */
    public function update(Request $request, $id)
    {
        $marryWish  = MarryWish::find($id);
        $marryWish->fill($request->all())->save();
        return $this->apiSuccess($marryWish);
    }

    /**
     * Remove the specified resource from storage.
     * @method DELETE
     * @param  int  $id
     */
    public function destroy($id)
    {
        $marryWish = MarryWish::find($id);
        if ($marryWish->delete()) {
            return $this->apiSuccess($marryWish);
        }
    }





}