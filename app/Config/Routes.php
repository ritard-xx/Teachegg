<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::processRegister'); // フォーム送信用のPOSTルートも追加

$routes->get('login', 'Auth::login'); // ログインフォームの表示
$routes->post('login', 'Auth::processLogin'); // ログイン処理
$routes->get('logout', 'Auth::logout'); // ログアウト処理

// ユーザープロフィール関連のルート
$routes->get('user', 'User::index'); // プロフィール表示
$routes->get('user/edit', 'User::edit'); // プロフィール編集フォーム表示
$routes->post('user/update', 'User::processProfileUpdate'); // プロフィール更新処理

