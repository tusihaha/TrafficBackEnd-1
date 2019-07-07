
<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', 'UsersController@mobileLogin');// đã làm
Route::post('/register', 'UsersController@mobileRegister');// dã làm
Route::get('/user/profile','UsersController@mobile_profile');// đã làm
Route::put('/user/editprofile', 'UsersController@mobile_profile_edit');// đã làm
Route::put('/user/changepassword', 'UsersController@change_password');// đã làm
Route::post('/user/avatar', 'UsersController@avatar');

Route::get('/getcurrent','MobileController@getcurrent');//đã làm
Route::get('/getfuture','MobileController@getfuture');//đã làm
Route::post('/marker','MobileController@addmarkers');//đã làm

Route::post('/user/report', 'MobileController@report');
Route::get('user/notification', 'MobileController@notification');

// api send data MARKERs table for team write algorithm using JAVA - Nam
Route::get('/data/markers', 'MarkerController@getdatamarkers');

// api get grid - Nam
Route::get('/data/rectangles', 'MarkerController@getdatarectangles');

// api thong ke so luong ban ghi cua moi user trong he thong - Manh
Route::get('/user/statistic','MobileController@userStatistic');

// api save data after calculate indicator - Manh
Route::post('/data/savedata','MobileController@saveData');
