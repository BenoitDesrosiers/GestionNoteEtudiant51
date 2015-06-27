<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddquestionbreakTpsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tps_questions', function($t)
		{
		
			$t->boolean('breakafter')->nullable();
		});	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tps_questions', function($t) 
		{
			$t->dropColumn('breakafter');
		});
	}

}
