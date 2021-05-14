<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//Route::rule('hello/:id','api/Test/hello');

//Banner
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');

//Theme
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');


//Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');
//Route::get('api/:version/product/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
//Route::get('api/:version/product/recent','api/:version.Product/getRecent');

//路由分组
Route::group('api/:version/product',function(){
    Route::get('/by_category','api/:version.Product/getAllInCategory');
    Route::get('/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
    Route::get('/recent','api/:version.Product/getRecent');
});

//Category
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

//Token
Route::post('api/:version/token/user','api/:version.Token/getToken');

//Address
Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');
//Route::get('api/:version/second','api/:version.Address/second');


//Order
Route::post('api/:version/order','api/:version.Order/placeOrder');

//Pay
Route::post('api/:version/pay/pre_order','api/:version.Pay/getPreOrder');