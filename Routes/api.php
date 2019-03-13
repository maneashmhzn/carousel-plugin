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

// Route::middleware('auth:api')->get('/carousel', function (Request $request) {
//     return $request->user();
// });


Route::group(['prefix' => 'admin/carousel_plugin','middleware'=>'api.auth','namespace'=>'Admin'], function () {
Route::post('add','AdminCarouselController@store');
Route::post('update','AdminCarouselController@update');
Route::post('delete','AdminCarouselController@destroy');
});
Route::group(['prefix' => 'carousel_plugin'], function () {
  Route::post('read','CarouselController@read');
});
