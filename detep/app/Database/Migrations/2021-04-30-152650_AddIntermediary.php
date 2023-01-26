<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIntermediary extends Migration
{
	public function up()
	{
		$this->db->disableForeignKeyChecks();
		$this->forge->addField([
            'int_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'int_fname' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'int_lname' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'int_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'int_mail' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true
				
            ],
            'int_password' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'int_station' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'int_device' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('int_id');
        $this->forge->addForeignKey('int_station', 'stations', 'st_id', 'cascade', 'cascade');
        $this->forge->createTable('intermediaries',true);
        $this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('intermediaries');
	}
}
