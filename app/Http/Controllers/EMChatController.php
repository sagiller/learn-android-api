<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\EMChatHelper;


class EMChatController extends RestController {

    /**
     * send a message to single target
     *
     * @method POST
     * @param  Request  $request
     */
    public function test(Request $request)
    {
        $response = EMChatHelper::getToken();
        if ($response->getStatusCode() == 200) {
            return $response->getBody();
        }
    }

    public function register(Request $request)
    {
        $username = "1dffd1e111x1325e48380";
        $password = "dfefefeef";
        return $this->apiSuccess(EMChatHelper::getToken());

    }
}