<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddConsumer extends Migration
{
	public function up()
	{
		$this->db->disableForeignKeyChecks();
		$this->forge->addField([
            'consumer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
			'consumer_device' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
                'null' => true,
            ],
			'consumer_usage' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('consumer_id');
        $this->forge->createTable('consumers',true);
	}

	public function down()
	{
		$this->forge->dropTable('consumers');
	}
}
