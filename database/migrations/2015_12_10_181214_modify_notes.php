<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyNotes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//enleve la réponse
		Schema::table('notes', function(Blueprint $t) 
		{
			$t->text('reponse')->change();
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notes', function(Blueprint $t) 
		{
			$t->string('reponse')->change();
		});
	}

}
