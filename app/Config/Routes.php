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

// 投稿関連のルート
$routes->get('posts', 'Post::index'); // 投稿一覧表示
$routes->get('posts/create', 'Post::create'); // 投稿フォーム表示
$routes->post('posts/create', 'Post::processCreate'); // 投稿作成処理