<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLevel extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'lv_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'lv_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
            ],
            'lv_int' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'unique' => true,
            ],
            'lv_description' => [
                'type' => 'TEXT',
                'null' => true
            ],
        ]);
        $this->forge->addKey('lv_id', true);
        $this->forge->createTable('levels',true);
	}

	public function down()
	{
		$this->forge->dropTable('levels');
	}
}
