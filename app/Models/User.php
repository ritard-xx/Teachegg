<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table      = 'users'; // このモデルが使用するデータベーステーブル
    protected $primaryKey = 'id';    // テーブルの主キー

    protected $useAutoIncrement = true; // 主キーが自動増分されるかどうか

    protected $returnType     = 'array'; // 戻り値のタイプ（arrayまたはobject）
    protected $useSoftDeletes = true; // ソフトデリートを使用するかどうか

    // 挿入または更新を許可するフィールド
    protected $allowedFields = [
        'username', // これが含まれているか
        'email',    // (ログインメールは更新しないかもしれないが念のため)
        'password', // (パスワードは別で更新するべき)
        'bio',      // これが含まれているか
        'faculty',  // これが含まれているか
        'department', // これが含まれているか
        'grade',    // これが含まれているか
        'major_subject', // これが含まれているか
        'profile_image' // これが含まれているか
    ];

    // タイムスタンプを自動的に管理するかどうか
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime'; // タイムスタンプの形式
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; // ソフトデリート用のフィールド

    // バリデーションルール (今回はスキップしますが、必要に応じて追加します)
    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;
}