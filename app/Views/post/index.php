<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿一覧 - Teachegg</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            width: 100%;
            max-width: 800px;
        }
        .header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background-color: #fff;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        .header h1 {
            color: #333;
            margin: 0;
        }
        .header .action-buttons a {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .header .action-buttons a:hover {
            background-color: #218838;
        }
        .post-card {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .post-header .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 1px solid #eee;
        }
        .post-header .username {
            font-weight: bold;
            color: #333;
        }
        .post-content p {
            margin: 0 0 15px 0;
            color: #555;
            line-height: 1.6;
            white-space: pre-wrap; /* 改行を保持 */
        }
        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-top: 10px;
            display: block; /* 画像の下の余白をなくす */
        }
        .post-footer {
            font-size: 0.9em;
            color: #888;
            text-align: right;
            margin-top: 15px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: left;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            width: 100%;
            max-width: 800px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="header">
            <h1>投稿一覧</h1>
            <div class="action-buttons">
                <a href="<?= url_to('Post::create') ?>">新規投稿</a>
                <a href="<?= url_to('User::index') ?>" style="background-color: #007bff; margin-left: 10px;">プロフィール</a>
                <a href="<?= url_to('Auth::logout') ?>" style="background-color: #dc3545; margin-left: 10px;">ログアウト</a>
            </div>
        </div>

        <?php if (empty($posts)): ?>
            <p style="text-align: center; color: #666;">まだ投稿がありません。最初の投稿をしてみましょう！</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <div class="post-header">
                        <img src="<?= $post['author_profile_image'] ? base_url('uploads/profiles/' . esc($post['author_profile_image'])) : base_url('images/default_profile.png') ?>" alt="プロフィール画像" class="profile-pic">
                        <span class="username"><?= esc($post['author_username']) ?></span>
                    </div>
                    <div class="post-content">
                        <?php if (!empty($post['content'])): ?>
                            <p><?= nl2br(esc($post['content'])) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?= base_url('uploads/posts/' . esc($post['image'])) ?>" alt="投稿画像">
                        <?php endif; ?>
                    </div>
                    <div class="post-footer">
                        投稿日時: <?= esc($post['created_at']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>