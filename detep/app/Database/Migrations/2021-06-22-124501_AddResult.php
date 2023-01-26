<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResult extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'res_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
			'res_device' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
			'res_station' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
			'res_action' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
			'res_responce' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'res_time DATETIME DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('res_id', true);
        $this->forge->createTable('results',true);
	}

	public function down()
	{
		$this->forge->dropTable('results');
	}
}
