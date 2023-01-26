<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDownload extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'down_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'down_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 0,
            ],
            'down_last DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('down_id', true);
        $this->forge->createTable('downloads',true);
	}

	public function down()
	{
		$this->forge->dropTable('downloads');
	}
}
