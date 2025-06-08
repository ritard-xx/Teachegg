<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LoginFilter implements FilterInterface
{
    /**
     * コントローラーのメソッド実行前に実行される処理
     * ユーザーがログインしているかチェックし、ログインしていなければリダイレクトします。
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return ResponseInterface|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // セッションからログイン状態をチェック
        // 'isLoggedIn' は Auth::processLogin でセッションに保存したキーです。
        if (! session()->get('isLoggedIn')) {
            // ログインしていない場合、ログインページにリダイレクト
            session()->setFlashdata('error', 'このページにアクセスするにはログインが必要です。');
            return redirect()->to('/login');
        }
    }

    /**
     * コントローラーのメソッド実行後に実行される処理
     * 今回は特別な処理は不要です。
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // 何もしません
    }
}