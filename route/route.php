<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;

Route::get('/', 'index/index/index');
Route::get('/xdebug', 'index/index/xdebug');
Route::group('api', function () {
    Route::group('user', function () {
        Route::get('/', 'index/user/getInfo');
        Route::post('/','index/user/create');
        Route::put('/', 'index/user/update');
        Route::delete('/','index/user/delete');
        Route::get('login','index/user/login');
    });

    Route::group('label', function () {
        Route::get('/:label_id','index/label/getOne');
        Route::post('/','index/label/create');
        Route::put('/:label_id', 'index/label/update');
        Route::delete('/:label_id','index/label/delete');
    });
    Route::get('/labels','index/label/getAll');

    Route::group('help', function () {
        Route::get('/:help_id','index/help/getOne');
        Route::post('/','index/help/create');
        Route::put('/:help_id', 'index/help/update');
        Route::delete('/:help_id','index/help/delete');
    });
    Route::get('/helps','index/help/getAll');

    Route::group('help_comment', function () {
        Route::get('/:help_comment_id','index/helpComment/getOne');
        Route::post('/','index/helpComment/create');
        Route::delete('/:help_comment_id','index/helpComment/delete');
    });
    Route::get('help_comments/:help_id','index/helpComment/getAll');






});

Route::miss('index/index/miss');


return [

];
