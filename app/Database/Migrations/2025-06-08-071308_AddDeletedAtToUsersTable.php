<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToUsersTable extends Migration
{
    public function up()
    {
        // deleted_at カラムを追加
        $fields = [
            'deleted_at' => [
                'type'       => 'datetime',
                'null'       => true,
                'after'      => 'updated_at', // updated_at の後に追加
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        // deleted_at カラムを削除 (マイグレーションのロールバック用)
        $this->forge->dropColumn('users', 'deleted_at');
    }
}
