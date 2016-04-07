<?php
/**
 * Created by PhpStorm.
 * User: hujj
 * Date: 2016/2/12
 * Time: 14:38
 */

namespace App\Http\Controllers;
use App\StarDetail;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Http\Input;
class TopicController extends RestController{
    /**
     * Display a listing of the resource.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $group_id = 0;$user_id = 0;
        if(isset($_GET['user_id'])){
            $user_id =  $_GET['user_id'];
        }
        if(isset($_GET['group_id'])){
            $group_id =  $_GET['group_id'];
        }

        if($user_id){
            $topics  = Topic::with("photos")->with("author")->where('user_id',$user_id)->where('parent_id',0)->paginate(40);

        }else if($group_id > 0){
            $topics  = Topic::with("photos")->with("author")->where('group_id',$group_id)->where('parent_id',0)->paginate(40);
        }else{
            return $this->apiError("参数不对","1001");
            die;
        }

        $star_user_id = $this->thisUserId();
        $i = 0;
        foreach($topics as $topic){
            $starDetail = StarDetail::where('topic_id',$topic->id)->where('user_id',$star_user_id)->first();
            if($starDetail && $starDetail['type'] = 1){
                $topics[$i]['star_type'] = 1;
            }else{
                $topics[$i]['star_type'] = 0;
            }
            $i++;
        }
        return $this->apiPagedSuccess($topics);
    }

    /**
     * Display a listing of the resource.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function getMarryWishs($marryId)
    {
        $topics  = Topic::with("photos")->with("author")->where('marry_id',$marryId)->where('parent_id',0)->paginate(4);


        return $this->apiPagedSuccess($topics);
    }

    /**
     * get a resource with resource id.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $topic = Topic::with("photos")->with("author")->find($id);
        if ($topic) {
            return $this->apiSuccess($topic);
        } else {
            return $this->apiError("未找到对应的婚庆wish数据","1001");
        }
    }

    /**
     * get a resource with resource id.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function reply($id)
    {
        $topic = Topic::with("photos")->with("author")->where('relate_id',$id)->paginate(4);
//        $topic = Topic::where('relate_id',$id)->paginate(4);
        if ($topic) {
            return $this->apiPagedSuccess($topic);
        } else {
            return $this->apiError("未找到对应的婚庆wish数据","1001");
        }

    }

    /**
     * get a resource with resource id.
     * @method POST
     * @return \Illuminate\Http\Response
     */
    public function star($id)
    {
        $user_id = 1;
        $star_type = 0;
        $starDetail = StarDetail::where("user_id",$user_id)->where('topic_id',$id)->first();
        if($starDetail){
            if($starDetail['type'] == 1){
                $res = Topic::where('id',$id)->decrement("star_count");
                $starDetail->type = '0';
                $star_type = 0;
            }else{
                $res = Topic::where('id',$id)->increment("star_count");
                $starDetail->type = '1';
                $star_type = 1;
            }
            $starDetail->save();
        }else{
            $res = Topic::where('id',$id)->increment("star_count");
            $starDetail = new StarDetail();
            $starDetail->user_id = $user_id;
            $starDetail->topic_id = $id;
            $starDetail->type = 1;
            $starDetail->save();
            $star_type = 1;
        }

        $topic = Topic::where('id',$id)->first( array("star_count"));
        $topic->star_type = $star_type;
        if ($topic) {
            return $this->apiSuccess($topic);
        } else {
            return $this->apiError("","1001");
        }

    }

    /**
     * Store a newly created resource in storage.
     * @method POST
     */
    public function create(Request $request)
    {
        $rules = [
            'group_id' => 'required',
            'title' => 'required',
            'content' => 'required'
        ];
        $this->validate($request, $rules);
        $input = $request->all();
        $input['user_id'] = $this->thisUserId();
        $topic = Topic::create($input);

        if ($topic) {
            $topic = Topic::with('photos','author')->find($topic->id);
            return $this->apiSuccess($topic);
        } else {
            return $this->apiError("save topic wish error",1001);
        }
    }
}