<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WebpageCategory;


class WebpageCategoryController extends RestController {
    public function modules()
    {
        $websites  = WebpageCategory::where('type','1')->with('icon')->paginate(15);
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