<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール編集 - Teachegg</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .edit-profile-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        .edit-profile-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group textarea,
        .form-group select {
            width: calc(100% - 22px); /* paddingとborderを考慮 */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-group input[type="file"] {
            padding: 5px 0;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .current-profile-image {
            display: block;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: 10px;
            border: 1px solid #eee;
        }
        .form-actions {
            text-align: center;
            margin-top: 30px;
        }
        .form-actions input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        .form-actions input[type="submit"]:hover {
            background-color: #218838;
        }
        .form-actions .back-button {
            background-color: #6c757d;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            margin-left: 10px;
            transition: background-color 0.3s ease;
        }
        .form-actions .back-button:hover {
            background-color: #5a6268;
        }
        .alert { /* 既存のalertスタイル */
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: left;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger { /* 既存のalert-dangerスタイル */
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .error-message { /* 既存のerror-messageスタイル */
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="edit-profile-container">
        <h2>プロフィール編集</h2>

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

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= url_to('User::processProfileUpdate') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="username">ユーザー名:</label>
                <input type="text" id="username" name="username" value="<?= old('username', $user['username']) ?>" required>
                <?php if (isset($errors['username'])): ?>
                    <div class="error-message"><?= $errors['username'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">メールアドレス:</label>
                <input type="email" id="email" name="email" value="<?= esc($user['email']) ?>" readonly disabled>
                <p style="font-size: 0.8em; color: #888;">※メールアドレスは変更できません。</p>
            </div>

            <div class="form-group">
                <label for="profile_image">プロフィール画像:</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*">
                <?php if ($user['profile_image']): ?>
                    <img src="<?= base_url('uploads/profiles/' . esc($user['profile_image'])) ?>" alt="現在のプロフィール画像" class="current-profile-image">
                <?php else: ?>
                    <img src="<?= base_url('images/default_profile.png') ?>" alt="デフォルト画像" class="current-profile-image">
                <?php endif; ?>
                <?php if (isset($errors['profile_image'])): ?>
                    <div class="error-message"><?= $errors['profile_image'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="bio">自己紹介:</label>
                <textarea id="bio" name="bio"><?= old('bio', $user['bio']) ?></textarea>
                <?php if (isset($errors['bio'])): ?>
                    <div class="error-message"><?= $errors['bio'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="faculty">学部:</label>
                <input type="text" id="faculty" name="faculty" value="<?= old('faculty', $user['faculty']) ?>">
                <?php if (isset($errors['faculty'])): ?>
                    <div class="error-message"><?= $errors['faculty'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="department">学科:</label>
                <input type="text" id="department" name="department" value="<?= old('department', $user['department']) ?>">
                <?php if (isset($errors['department'])): ?>
                    <div class="error-message"><?= $errors['department'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="grade">学年:</label>
                <input type="text" id="grade" name="grade" value="<?= old('grade', $user['grade']) ?>">
                <?php if (isset($errors['grade'])): ?>
                    <div class="error-message"><?= $errors['grade'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="major_subject">専攻教科:</label>
                <input type="text" id="major_subject" name="major_subject" value="<?= old('major_subject', $user['major_subject']) ?>">
                <?php if (isset($errors['major_subject'])): ?>
                    <div class="error-message"><?= $errors['major_subject'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <input type="submit" value="更新">
                <a href="<?= url_to('User::index') ?>" class="back-button">キャンセル</a>
            </div>
        </form>
    </div>
</body>
</html>