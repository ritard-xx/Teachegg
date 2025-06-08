<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User as UserModel; // Userモデルを使用
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    protected $helpers = ['form', 'url']; // フォームヘルパーとURLヘルパーを使用

    /**
     * このコントローラー内の全てのメソッドで 'isLoggedIn' フィルターを適用します。
     * これにより、ログインしていないユーザーはこのコントローラーのページにアクセスできません。
     */
    protected $beforeFilters = ['isLoggedIn'];

    /**
     * ログインしているユーザーのプロフィールページを表示します。
     *
     * @return string
     */
    public function index()
    {
        $userModel = new UserModel();
        // セッションからログインしているユーザーのIDを取得
        $loggedInUserId = session()->get('userId');

        // データベースからユーザー情報を取得
        $user = $userModel->find($loggedInUserId);

        if (! $user) {
            // ユーザーが見つからない場合はエラーまたはリダイレクト
            session()->setFlashdata('error', 'ユーザー情報が見つかりませんでした。');
            return redirect()->to('/'); // 例: トップページにリダイレクト
        }

        // プロフィールビューにユーザー情報を渡して表示
        return view('user/profile', ['user' => $user]);
    }

    /**
     * ログインしているユーザーのプロフィール編集フォームを表示します。
     *
     * @return string|ResponseInterface
     */
    public function edit()
    {
        $userModel = new UserModel();
        $loggedInUserId = session()->get('userId');

        $user = $userModel->find($loggedInUserId);

        if (! $user) {
            session()->setFlashdata('error', 'ユーザー情報が見つかりませんでした。');
            return redirect()->to('/');
        }

        // プロフィール編集ビューにユーザー情報を渡して表示
        return view('user/edit_profile', ['user' => $user]);
    }

    /**
     * プロフィール編集フォームからのデータを受け取り、ユーザー情報を更新します。
     *
     * @return ResponseInterface
     */
    public function processProfileUpdate()
    {
        $userModel = new UserModel();
        $loggedInUserId = session()->get('userId');

        // 1. バリデーションルールの定義
        $rules = [
            'username'      => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,' . $loggedInUserId . ']', // 自分のユーザー名は許可
            'bio'           => 'permit_empty|max_length[500]', // 自己紹介は空も許可、最大500文字
            'faculty'       => 'permit_empty|max_length[100]',
            'department'    => 'permit_empty|max_length[100]',
            'grade'         => 'permit_empty|max_length[50]',
            'major_subject' => 'permit_empty|max_length[100]',
            'profile_image' => 'is_image[profile_image]|mime_in[profile_image,image/jpg,image/jpeg,image/png,image/gif]|max_size[profile_image,2048]', // 画像バリデーション
        ];

        // 2. 入力データのバリデーション実行
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. ユーザー情報の取得
        $user = $userModel->find($loggedInUserId);
        if (! $user) {
            session()->setFlashdata('error', 'ユーザー情報が見つかりませんでした。');
            return redirect()->to('/');
        }

        // 4. 更新するデータの準備
        $data = [
            'username'      => $this->request->getPost('username'),
            'bio'           => $this->request->getPost('bio'),
            'faculty'       => $this->request->getPost('faculty'),
            'department'    => $this->request->getPost('department'),
            'grade'         => $this->request->getPost('grade'),
            'major_subject' => $this->request->getPost('major_subject'),
        ];

        // 5. プロフィール画像のアップロード処理
        $file = $this->request->getFile('profile_image');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            // 古いプロフィール画像があれば削除
            if ($user['profile_image']) {
                $oldImagePath = FCPATH . 'uploads/profiles/' . $user['profile_image'];
                if (file_exists($oldImagePath) && ! is_dir($oldImagePath)) {
                    unlink($oldImagePath); // ファイルを削除
                }
            }

            // 新しいファイル名を生成 (重複を避けるため)
            $newName = $file->getRandomName();
            // ファイルを保存するディレクトリ
            $uploadPath = FCPATH . 'uploads/profiles/'; // public/uploads/profiles/ に保存

            // ディレクトリが存在しない場合は作成
            if (! is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // ファイルを移動
            $file->move($uploadPath, $newName);
            $data['profile_image'] = $newName; // データベースに保存するファイル名をセット
        }

        // 6. データベースのユーザー情報を更新
        if ($userModel->update($loggedInUserId, $data)) {
            // セッションのユーザー名も更新 (プロフィール表示のため)
            session()->set('username', $data['username']);
            session()->setFlashdata('success', 'プロフィールが正常に更新されました！');
            return redirect()->to(url_to('User::index')); // プロフィール表示ページへリダイレクト
        } else {
            session()->setFlashdata('error', 'プロフィールの更新に失敗しました。');
            return redirect()->back()->withInput();
        }
    }
}