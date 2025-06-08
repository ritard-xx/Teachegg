<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($user['username']) ?>さんのプロフィール - Teachegg</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .profile-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 2px solid #ddd;
        }
        .profile-header h2 {
            margin: 10px 0;
            color: #333;
        }
        .profile-detail {
            margin-bottom: 15px;
        }
        .profile-detail p {
            margin: 5px 0;
            color: #555;
            line-height: 1.6;
        }
        .profile-detail strong {
            color: #333;
            display: inline-block;
            width: 120px; /* ラベルの幅を固定 */
        }
        .profile-action {
            text-align: center;
            margin-top: 30px;
        }
        .profile-action a {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .profile-action a:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: left;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="profile-container">
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

        <div class="profile-header">
            <img src="<?= $user['profile_image'] ? base_url('uploads/profiles/' . esc($user['profile_image'])) : base_url('images/default_profile.png') ?>" alt="プロフィール画像">
            <h2><?= esc($user['username']) ?></h2>
            <p><?= esc($user['email']) ?></p>
        </div>

        <div class="profile-detail">
            <p><strong>学部:</strong> <?= esc($user['faculty'] ?? '未設定') ?></p>
            <p><strong>学科:</strong> <?= esc($user['department'] ?? '未設定') ?></p>
            <p><strong>学年:</strong> <?= esc($user['grade'] ?? '未設定') ?></p>
            <p><strong>専攻教科:</strong> <?= esc($user['major_subject'] ?? '未設定') ?></p>
            <p><strong>自己紹介:</strong><br><?= nl2br(esc($user['bio'] ?? 'まだ自己紹介がありません。')) ?></p>
        </div>

        <div class="profile-action">
            <a href="<?= url_to('User::edit') ?>">プロフィールを編集する</a>
            <a href="<?= url_to('Auth::logout') ?>" style="background-color: #dc3545; margin-left: 10px;">ログアウト</a>
        </div>
    </div>
</body>
</html>