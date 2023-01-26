<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddParcel extends Migration
{
	public function up()
	{
		$this->db->disableForeignKeyChecks();
		$this->forge->addField([
            'parc_id' => [
                'type' => 'VARCHAR',
                'constraint' => '40',
                'unique' => true,
            ],
            'parc_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'parc_parent' => [
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => true,
                'default' => null,
            ],
            'parc_product' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'parc_level' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'parc_station_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'parc_next_station' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'parc_sent_dates' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'parc_arrival_dates' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'parc_qrcodelink' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'parc_sold' => [
                'type' => 'INT',
                'constraint' => '11',
                'null' => true,
                'default' => 0,
            ],
        ]);
        $this->forge->addPrimaryKey('parc_id');
        $this->forge->addForeignKey('parc_parent', 'parcels', 'parc_id', 'cascade', 'cascade');
        $this->forge->addForeignKey('parc_product', 'products', 'prod_id', 'cascade', 'cascade');
        $this->forge->createTable('parcels',true);
        $this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('parcels');
	}
}
