<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true, // メールアドレスは一意にする
                'null'       => false,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255', // ハッシュ化されたパスワードを保存するため長めに
                'null'       => false,
            ],
            'username' => [ // ユーザー名やニックネーム
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true, // ユーザー名も一意にする
                'null'       => true, // 必須でなければfalseにする
            ],
            'faculty' => [ // 学部
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'department' => [ // 学科
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'grade' => [ // 学年
                'type'       => 'VARCHAR', // またはINT
                'constraint' => '50',
                'null'       => true,
            ],
            // プロフィール項目
            'profile_image' => [ // アイコン画像のファイルパス
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'bio' => [ // 自己紹介
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'major_subject' => [ // 専攻教科
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'created_at' => [
                'type'       => 'datetime',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'datetime',
                'null'       => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users'); // users テーブルを作成
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
