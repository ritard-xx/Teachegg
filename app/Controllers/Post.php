<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Post as PostModel; // Postモデルを使用
use App\Models\User; // 投稿ユーザー情報を取得するため
use CodeIgniter\HTTP\ResponseInterface;

class Post extends BaseController
{
    protected $helpers = ['form', 'url']; // フォームヘルパーとURLヘルパーを使用

    /**
     * このコントローラー内の全てのメソッドで 'isLoggedIn' フィルターを適用します。
     * これにより、ログインしていないユーザーはこのコントローラーのページにアクセスできません。
     */
    protected $beforeFilters = ['isLoggedIn'];

    /**
     * 新しい投稿を作成するフォームを表示します。
     *
     * @return string
     */
    public function create()
    {
        return view('post/create');
    }

    /**
     * 投稿フォームからのデータを受け取り、新しい投稿を保存します。
     *
     * @return ResponseInterface
     */
    public function processCreate()
    {
        $postModel = new PostModel();
        $loggedInUserId = session()->get('userId'); // ログインしているユーザーのID

        // 1. バリデーションルールの定義
        $rules = [
            'content' => 'permit_empty|max_length[1000]', // テキストは空も許可、最大1000文字
            'image'   => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]|max_size[image,4096]|ext_in[image,jpg,jpeg,png,gif]', // 画像バリデーション
        ];

        // 画像がアップロードされていない場合は 'uploaded' ルールを削除（テキストのみの投稿を許可するため）
        if (! $this->request->getFile('image')->isValid()) {
            unset($rules['image']);
        }

        // 2. 入力データのバリデーション実行
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id' => $loggedInUserId,
            'content' => $this->request->getPost('content'),
        ];

        // 3. 画像ファイルのアップロード処理
        $file = $this->request->getFile('image');
        $imageName = null;

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $uploadPath = FCPATH . 'uploads/posts/'; // public/uploads/posts/ に保存

            // ディレクトリが存在しない場合は作成
            if (! is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $newName);
            $imageName = $newName;
            $data['image'] = $imageName; // データベースに保存するファイル名をセット
        }

        // テキストと画像の両方が空の場合はエラー
        if (empty($data['content']) && empty($data['image'])) {
            session()->setFlashdata('error', '投稿内容または画像が必要です。');
            return redirect()->back()->withInput();
        }

        // 4. データベースに投稿を保存
        if ($postModel->insert($data)) {
            session()->setFlashdata('success', '投稿が完了しました！');
            return redirect()->to(url_to('Post::index')); // 投稿一覧ページへリダイレクト
        } else {
            session()->setFlashdata('error', '投稿に失敗しました。');
            return redirect()->back()->withInput();
        }
    }

    /**
     * 全ての投稿を一覧表示します。
     *
     * @return string
     */
    public function index()
    {
        $postModel = new PostModel();
        $userModel = new User(); // 投稿ユーザー名を取得するため

        // 最新の投稿を新しい順に取得
        $posts = $postModel->orderBy('created_at', 'DESC')->findAll();

        // 各投稿にユーザー情報を紐付ける
        foreach ($posts as $key => $post) {
            $author = $userModel->find($post['user_id']);
            $posts[$key]['author_username'] = $author['username'] ?? '不明なユーザー';
            $posts[$key]['author_profile_image'] = $author['profile_image'] ?? null;
        }

        return view('post/index', ['posts' => $posts]);
    }
}