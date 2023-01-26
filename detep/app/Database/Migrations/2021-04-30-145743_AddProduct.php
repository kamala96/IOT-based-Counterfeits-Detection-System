<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProduct extends Migration
{
    public function up()
	{
		$this->db->disableForeignKeyChecks();
		$this->forge->addField([
            'prod_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'prod_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'prod_slug' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'prod_category' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'prod_system' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'prod_regdate DATETIME DEFAULT CURRENT_TIMESTAMP',
            'prod_updatedat DATETIME',
            'prod_deletedat DATETIME',
        ]);
        $this->forge->addKey('prod_id', true);
        $this->forge->addForeignKey('prod_category', 'categories', 'cat_id', 'cascade', 'cascade');
        $this->forge->addForeignKey('prod_system', 'systems', 'sy_id', 'cascade', 'cascade');
        $this->forge->createTable('products',true);
        $this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('products');
	}
}
