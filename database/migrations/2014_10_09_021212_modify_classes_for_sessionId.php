<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyClassesForSessionId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('classes', function($table)
		{
			$table->dropColumn('session');
			$table->unsignedInteger('sessionscholaire_id')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('classes', function($table)
		{
			$table->dropColumn('sessionscholaire_id');
			$table->string('session');
		});	
	}

}
