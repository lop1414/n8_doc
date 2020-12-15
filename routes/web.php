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

$router->get('/', function () use ($router) {
    return 'hello';
});


// 后台
$router->group([
    // 路由前缀
    'prefix' => 'admin',
    // 路由中间件
    'middleware' => ['center_menu_auth', 'admin_request_log', 'access_control_allow_origin']
], function () use ($router) {
    // 文章
    $router->group(['prefix' => 'article'], function () use ($router) {
        $router->post('select', 'Admin\ArticleController@select');
        $router->post('read', 'Admin\ArticleController@read');
        $router->post('create', 'Admin\ArticleController@create');
        $router->post('update', 'Admin\ArticleController@update');
        $router->post('enable', 'Admin\ArticleController@enable');
        $router->post('disable', 'Admin\ArticleController@disable');
        $router->post('tree', 'Admin\ArticleController@tree');
    });
});


// 前台接口
$router->group([
    // 路由前缀
    'prefix' => 'front',
    // 路由中间件
    'middleware' => ['center_login_auth', 'access_control_allow_origin']
], function () use ($router) {
    $router->group(['prefix' => 'article'], function () use ($router) {
        // 文章
        $router->post('get', 'Front\ArticleController@get');
        $router->post('tree', 'Front\ArticleController@tree');
        $router->post('read', 'Front\ArticleController@read');
    });
});
