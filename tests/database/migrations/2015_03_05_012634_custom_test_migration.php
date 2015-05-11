<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomTestMigration extends Migration {
	public $type = 'custom';

	public function up()
	{
		Schema::create('test_tbl', function(\Illuminate\Database\Schema\Blueprint $table){
			$table->integer('id');
			$table->string('test_val');
		});

		DB::connection($this->getConnection())->table('test_tbl')
			->insert(array(
				'id' => 1,
				'test_val' => 'custom'
			));
	}

	public function down()
	{
		Schema::dropIfExists('test_tbl');
	}

}
