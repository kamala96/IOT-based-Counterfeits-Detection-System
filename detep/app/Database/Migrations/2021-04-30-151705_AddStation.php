<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStation extends Migration
{
	public function up()
	{
		$this->db->disableForeignKeyChecks();
		$this->forge->addField([
            'st_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'st_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'st_description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'st_level' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'st_system' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'st_beacon' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'st_lat' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'st_lon' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],

        ]);
        $this->forge->addPrimaryKey('st_id');
        $this->forge->addForeignKey('st_level', 'levels', 'lv_id', 'cascade', 'cascade');
        $this->forge->addForeignKey('st_system', 'systems', 'sy_id', 'cascade', 'cascade');
        $this->forge->createTable('stations',true);
        $this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('stations');
	}
}
