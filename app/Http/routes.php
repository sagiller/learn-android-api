<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->group(['prefix' => 'api/v1','middleware' => 'auth','namespace' => 'App\Http\Controllers'], function($app)
{
    $app->put('user','UserController@update');
    $app->get('user/{id}','UserController@show');
    $app->put('user/password','UserController@updatePassword');


    $app->post('message','MessageController@send');

    $app->get('letter','LetterController@index');
    $app->get('letter/{id}','LetterController@show');
    $app->post('letter','LetterController@create');
    $app->put('openlove/open', 'OpenLoveController@open');
    $app->put('letter/{id}','LetterController@update');
    $app->delete('letter/{id}','LetterController@destroy');

    $app->get('marry','MarryController@index');
    $app->get('marry/{id}','MarryController@show');
    $app->get('marry/qrcodecontent/{qr_code_content}','MarryController@showByQrCodeContent');
    $app->post('marry','MarryController@create');
    $app->put('marry/{id}','MarryController@update');
    $app->delete('marry/{id}','MarryController@destroy');
    $app->delete('marry/{id}','MarryWishController@destroy');

    $app->get('marrywish','MarryWishController@index');
    $app->get('marrywish/{id}','MarryWishController@show');
    $app->get('marrywish/marry/{marryId}','MarryWishController@indexByMarryId');
    $app->post('marrywish','MarryWishController@create');
    $app->put('marrywish/{id}','MarryWishController@update');


    $app->get('image/{id}','ImageController@show');
    $app->post('image','ImageController@create');
    $app->put('image/{id}','ImageController@update');
    $app->delete('image/{id}','ImageController@destroy');

    $app->get('user/{id}/friend','UserFriendController@index');
    $app->get('user/friend/timeline','UserFriendController@timeline');
    $app->get('user/friend/{friendUserId}/letter/timeline','LetterController@timeline');
    $app->get('user/{friendUserId}/letter','LetterController@letters');
    $app->post('userfriend','UserFriendController@create');
    $app->delete('userfriend/user/{user_id}/friend/{friend_user_id}','UserFriendController@delete');

    $app->get('marry/{marryId}/marrywish','TopicController@getMarryWishs');
    $app->get('topic/{id}','TopicController@show');
    $app->get('reply/{id}','TopicController@reply');
    $app->post('topic','TopicController@create');
    $app->post('star/{id}','TopicController@star');
    $app->get('reply/{id}','TopicController@reply');
    $app->get('topic','TopicController@index');
    $app->post('star/{id}','TopicController@star');
});

$app->group(['prefix' => 'api/v1','namespace' => 'App\Http\Controllers'], function($app)
{
    $app->get('image','ImageController@index');
    $app->get('emchat','EMChatController@register');
    $app->post('user/login','UserController@login');
    $app->post('user/register','UserController@register');
    $app->post('user/getRegisterSms','UserController@getRegisterSms');

    $app->get('webpagecategory/{categoryId}/webpages','WebpageController@webpages');
    $app->get('webpagecategory/modules','WebpageCategoryController@modules');
});


