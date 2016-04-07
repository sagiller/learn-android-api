<?php
namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use Illuminate\Http\Request;
use App\Image;
use App\Letter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImageController extends RestController {
    /**
     * Display a listing of the resource.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images  = Image::all();
        return $this->apiSuccess($images);
    }

    /**
     * Store a newly created resource in storage.
     * @method POST
     */
    public function create(Request $request)
    {
        //validate
        $rules = [
            'owner_id' => 'required',
            'default_type' => 'required',
            //'deleted_image_paths[0]' => 'optional',
            //'images[0]' => 'optional',
            //'type[0]' => 'optional',
        ];
        $this->validate($request, $rules);
        if (!$request->hasFile("images")) {
            //return $this->apiError("没有传入图片资源","1001");
        }

        //delete images
        $deletedImagePaths = $request->get('deleted_image_paths');
        if ($deletedImagePaths) {
            foreach($deletedImagePaths as $key=>$deletedImagePath) {
                Image::where('path',$deletedImagePath)->delete();
            }

        }
        //upload images
        $files = $request->file('images');
        //$images = array();
        if ($files) {
            foreach($files as $key=>$file){
                //upload file
                $extension = $file->getClientOriginalExtension();
                if ($extension != "jpg" && $extension != "png"){
                    break;
                }
                $filename  = time(). StringHelper::generate_random_code(7) . '.' . $extension;
                $path = 'upload/' . $filename;
                $realPath =  rtrim(app()->basePath('public/'.$path), '/');
                \Intervention\Image\Facades\Image::make($file->getRealPath())->save($realPath);

                //generate entity
                $entity = new Image();
                $entity->owner_id = $request['owner_id'];
                if (isset($request['type'][$key])) {
                    $entity->type = $request['type'][$key];
                } else {
                    $entity->type = $request['default_type'];
                }
                switch ($entity->type) {
                    case 'user_avatar':
                    $entity->owner_type = 'App\User';
                        break;
                    case 'letter_to_user_avatar':
                    case 'letter_photos':
                    $entity->owner_type = 'App\Letter';
                        break;
                    case 'marrywish_photos':
                    $entity->owner_type = 'App\MarryWish';
                        break;
                    case 'marry_couple':
                    case 'marry_photos':
                    $entity->owner_type = 'App\Marry';
                        break;
                    case 'topic_photos':
                        $entity->owner_type  = 'App\Topic';
                        break;
                    default:
                        break;
                }
                $resultMap['owner_type'] = $entity->owner_type;
                $resultMap['owner_id'] = $entity->owner_id;
                $entity->path = $path;

                //check create or update,then save
                if ($entity->type == 'marry_couple' || $entity->type == 'user_avatar' || $entity->type == 'letter_to_user_avatar') {
                    $map['owner_id'] = $entity->owner_id;
                    $map['type'] = $entity->type;
                    $image = Image::where($map)->first();
                    if ($image) {
                        $image->path = $entity->path;
                        $image->save();
                        //$images[] = $image;
                    } else {
                        $entity->save();
                        //$images[] = $entity;
                    }

                } else {
                    $entity->save();
                    //$images[] = $entity;
                }

            }
        }
        $images = Image::where($resultMap)->get();
        return $this->apiSuccess($images);

    }

}