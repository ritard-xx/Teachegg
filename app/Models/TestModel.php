<?php

namespace App\Models;

use CodeIgniter\Model;

class TestModel extends Model
{
    // このモデルが連携するデータベーステーブルの名前
    protected $table = 'todos';

    // テーブルのプライマリーキー
    protected $primaryKey = 'id';

    // プライマリーキーが自動増分（AUTO_INCREMENT）されるかどうか
    protected $useAutoIncrement = true;

    // 検索結果の戻り値のタイプ（'array' または 'object'）
    protected $returnType = 'array';

    // 論理削除（ソフトデリート）を使用するかどうか
    // 'false' ではなく `false` (ブール値) であることに注意
    protected $useSoftDeletes = false;

    // 挿入・更新を許可するフィールドのリスト
    // ここにリストされていないフィールドは、insert/update時に無視されます
    protected $allowedFields = ['title', 'description']; // created_at, updated_at はTimestampsで自動設定されるため、通常は不要

    // タイムスタンプ（created_at, updated_at）を自動管理するかどうか
    protected $useTimestamps = true;

    // タイムスタンプのデータ形式
    protected $dateFormat = 'datetime';

    // レコード作成時に自動設定されるフィールド名
    protected $createdField = 'created_at';

    // レコード更新時に自動設定されるフィールド名
    protected $updatedField = 'updated_at';

    // レコード削除時に自動設定されるフィールド名（useSoftDeletesがtrueの場合のみ使用）
    // protected $deletedField = 'deleted_at'; // useSoftDeletesがfalseなので、今回は不要
}