<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSystem extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'sy_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'sy_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
            ],
            'sy_slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'unique' => true,
            ],
            'sy_description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'sy_action' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 0,
            ],
            'sy_mg' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
        ]);
        $this->forge->addKey('sy_id', true);
        $this->forge->createTable('systems',true);
	}

	public function down()
	{
		$this->forge->dropTable('systems');
	}
}
