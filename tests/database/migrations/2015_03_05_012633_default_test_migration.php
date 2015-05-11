<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefaultTestMigration extends Migration {
	public function up()
	{
		Schema::create('test_tbl', function(Blueprint $table){
			$table->integer('id');
			$table->string('test_val');
		});

		DB::connection($this->getConnection())->table('test_tbl')
			->insert(array(
				'id' => 1,
				'test_val' => 'default'
			));
	}

	public function down()
	{
		Schema::dropIfExists('test_tbl');
	}

}
