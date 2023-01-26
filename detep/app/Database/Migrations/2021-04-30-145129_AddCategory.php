<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategory extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'cat_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'cat_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
            ],
            'cat_slug' => [
                'type' => 'TEXT',
                'null' => true
            ],
        ]);
        $this->forge->addKey('cat_id', true);
        $this->forge->createTable('categories',true);
	}

	public function down()
	{
		$this->forge->dropTable('categories');
	}
}
