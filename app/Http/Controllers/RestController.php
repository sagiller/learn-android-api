<?php
namespace App\Http\Controllers;
use App\User;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseHelper;

class RestController extends BaseController {
	public function apiSuccess($content) {
        return ResponseHelper::apiSuccess($content);
	}

    public function apiPagedSuccess($content) {
        return ResponseHelper::apiPagedSuccess($content);
    }

	public function apiError($content,$code) {

        return ResponseHelper::apiError($content,$code);
	}

    public function thisUserId() {
        return Auth::user()->id;
    }

    public function thisUser() {
        $id = Auth::user()->id;
        return User::with('emchat_user')->find($id);
    }
}