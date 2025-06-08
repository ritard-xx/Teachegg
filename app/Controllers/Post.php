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

    /**
     * 指定されたIDの投稿を削除します。
     *
     * @param int $id 削除する投稿のID
     * @return ResponseInterface
     */
    public function delete(int $id)
    {
        $postModel = new PostModel();
        $loggedInUserId = session()->get('userId');

        // 1. 投稿を取得し、存在するか確認
        $post = $postModel->find($id);

        if (! $post) {
            session()->setFlashdata('error', '投稿が見つかりませんでした。');
            return redirect()->to(url_to('Post::index'));
        }

        // 2. ログインユーザーが投稿の所有者であるか確認
        if ($post['user_id'] !== $loggedInUserId) {
            session()->setFlashdata('error', 'この投稿を削除する権限がありません。');
            return redirect()->to(url_to('Post::index'));
        }

        // 3. 関連する画像ファイルがあれば削除
        if (!empty($post['image'])) {
            $imagePath = FCPATH . 'uploads/posts/' . $post['image'];
            if (file_exists($imagePath) && ! is_dir($imagePath)) {
                unlink($imagePath); // ファイルを削除
            }
        }

        // 4. データベースから投稿を削除 (ソフトデリート)
        if ($postModel->delete($id)) { // useSoftDeletesがtrueなので、deleted_atが更新される
            session()->setFlashdata('success', '投稿が正常に削除されました。');
        } else {
            session()->setFlashdata('error', '投稿の削除に失敗しました。');
        }

        return redirect()->to(url_to('Post::index'));
    }
}