<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClasses extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('classes', function($table)
		{
			$table->increments('id');
			$table->string('code');
			$table->string('nom');
			$table->string('session');
			$table->string('groupe');
			$table->string('local');
			$table->timestamps();
		});
	}
	

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('classes');
	}

}
