<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    // Account is not activated
    Route::group(['middleware' => 'account-not-activated'], function () {
        Route::get('/activate/account/', ['as' => 'not.activated.account', 'uses' => 'ActivateAccountController@index']);
        Route::get('/activate/{token}', ['as' => 'activate.account', 'uses' => 'ActivateAccountController@activate']);
        Route::get('/activate/account/resend/', ['as' => 'resend.activate.message', 'uses' => 'ActivateAccountController@resend']);
    });

    // Account activated
    Route::group(['middleware' => 'account-activated'], function () {
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/home/logout', 'HomeController@logout')->name('home_logout');
        Route::post('/home/logout', 'HomeController@logout')->name('home_logout');
        Route::post('/home/new/photo', 'HomeController@newPhoto')->name('new_photo');
        Route::get('/home/photos/view', 'PhotosController@index')->name('photos_view');
        Route::get('/home/users/view', 'UsersController@index')->name('users_view');
        Route::get('/home/friends/view', 'FriendsController@index')->name('friends_view');
        Route::get('/home/friend/requests', 'FriendsController@requests')->name('friend_requests');
        Route::get('/home/chat/{id}', 'ChatController@index')->name('chat');
        Route::post('/home/change/main/photo', 'PhotosController@changeMainPhoto')->name('change_main_photo');
        Route::post('/home/delete/photo', 'PhotosController@deletePhoto')->name('delete_photo');
        Route::post('/home/add/friend', 'FriendsController@addFriend')->name('add_friend');
        Route::post('/home/confirm/friend', 'FriendsController@confirmFriend')->name('confirm_friend');
        Route::post('/home/delete/friend/request', 'FriendsController@deleteFriendRequest')->name('confirm_friend_request');
    });
});

