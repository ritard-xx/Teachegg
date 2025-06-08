<?php

namespace App\Controllers;

use App\Controllers\BaseController; // BaseControllerを継承
use CodeIgniter\HTTP\ResponseInterface; // レスポンスインターフェースを使用
use App\Models\User; // Userモデルを使用

class Auth extends BaseController
{
    /**
     * ユーザー登録フォームを表示します。
     *
     * @return string
     */
    public function register()
    {
        // ユーザー登録フォームのビューをロードして表示します。
        // ビューファイルは app/Views/auth/register.php に作成します。
        return view('auth/register');
    }

    /**
     * ユーザー登録フォームからのデータを受け取り、処理します。
     *
     * @return ResponseInterface
     */
    public function processRegister()
    {
        // 1. ヘルパーをロード: フォームのバリデーションなどに必要です。
        helper(['form', 'url']);

        // 2. バリデーションルールの定義
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'email'    => 'required|valid_email|is_unique[users.email]', // usersテーブルのemailカラムでユニークであること
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required_with[password]|matches[password]', // passwordと同じであること
        ];

        // 3. 入力データのバリデーション実行
        // バリデーションに失敗した場合
        if (! $this->validate($rules)) {
            // バリデーションエラーをセッションにフラッシュデータとして保存し、
            // 登録フォームにリダイレクトしてエラーメッセージを表示させます。
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 4. バリデーション成功後の処理
        $userModel = new User(); // Userモデルのインスタンスを作成

        // フォームから送信されたデータを取得
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // パスワードをハッシュ化
        ];

        // 5. データベースにユーザー情報を保存
        if ($userModel->insert($data)) {
            // 成功メッセージをセッションに保存して、トップページにリダイレクト
            session()->setFlashdata('success', 'ユーザー登録が完了しました！');
            return redirect()->to('/'); // 登録成功後のリダイレクト先 (例: トップページ)
        } else {
            // 失敗メッセージをセッションに保存して、登録フォームにリダイレクト
            session()->setFlashdata('error', 'ユーザー登録に失敗しました。もう一度お試しください。');
            return redirect()->back()->withInput();
        }

        // ここにユーザー登録処理のロジックを実装します。
        // 今はまだ空の状態です。
        echo "ユーザー登録処理を実行します。";
        return $this->response->setStatusCode(ResponseInterface::HTTP_OK);
    }
}