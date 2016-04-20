<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Webpage;


class WebpageController extends RestController {
    public function websites($categoryId)
    {
        $websites  = Webpage::where('category_id',$categoryId)->with('category','icon')->paginate(15);

        return $this->apiPagedSuccess($websites);
    }

    public function webpages($categoryId)
    {
        $websites  = Webpage::where('category_id',$categoryId)->with('category','icon')->paginate(15);
        return $this->apiPagedSuccess($websites);
    }

    /**
     * get a resource with resource id.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


    }


    /**
     * Store a newly created resource in storage.
     * @method POST
     */
    public function create(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     * @method PUT
     * @param  int $id
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     * @method DELETE
     * @param  int  $id
     */
    public function destroy($id)
    {

    }





}