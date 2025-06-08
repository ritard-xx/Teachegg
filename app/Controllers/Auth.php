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
    }

        /**
         * ログインフォームを表示します。
         *
         * @return string
         */
        public function login()
        {
            // ログインフォームのビューをロードして表示します。
            // ビューファイルは app/Views/auth/login.php に作成します。
            return view('auth/login');
        }

        /**
     * ログインフォームからのデータを受け取り、処理します。
     *
     * @return ResponseInterface
     */
    public function processLogin()
    {
        // 1. ヘルパーをロード
        helper(['form', 'url']);

        // 2. バリデーションルールの定義
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        // 3. 入力データのバリデーション実行
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 4. 認証処理
        $userModel = new User();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // メールアドレスでユーザーを検索
        $user = $userModel->where('email', $email)->first();

        // ユーザーが見つかり、かつパスワードが一致する場合
        if ($user && password_verify($password, $user['password'])) {
            // ログイン成功: セッションにユーザー情報を保存
            // CodeIgniter 4 のセッションは $session = session(); で取得
            $session = session();
            $session->set([
                'isLoggedIn' => true,
                'userId'     => $user['id'],
                'username'   => $user['username'],
                'email'      => $user['email'],
                // 必要に応じて他のユーザー情報もセッションに保存
            ]);

            session()->setFlashdata('success', 'ログインしました！');
            return redirect()->to('/'); // ログイン成功後のリダイレクト先 (例: トップページやダッシュボード)
        } else {
            // ログイン失敗
            session()->setFlashdata('error', 'メールアドレスまたはパスワードが正しくありません。');
            return redirect()->back()->withInput();
        }
    }

    /**
     * ログアウト処理を行います。
     *
     * @return ResponseInterface
     */
    public function logout()
    {
        $session = session();
        $session->destroy(); // セッションを破棄

        session()->setFlashdata('success', 'ログアウトしました。');
        return redirect()->to('/login'); // ログアウト後のリダイレクト先 (例: ログインページ)
    }
}