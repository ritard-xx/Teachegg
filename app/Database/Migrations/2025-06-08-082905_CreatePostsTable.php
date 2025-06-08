<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [ // どのユーザーが投稿したかを示す外部キー
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'content' => [ // 投稿のテキスト内容
                'type'           => 'TEXT',
                'null'           => true, // テキストは必須ではない可能性も考慮（画像のみ投稿の場合など）
            ],
            'image' => [ // 投稿に添付される画像のファイル名
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true, // 画像は必須ではない可能性も考慮
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime null', // ソフトデリート用
        ]);

        $this->forge->addPrimaryKey('id');
        // user_id に外部キー制約を追加
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('posts');
    }

    public function down()
    {
        $this->forge->dropTable('posts');
    }
}
