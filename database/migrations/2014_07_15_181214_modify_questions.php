<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyQuestions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//j'enlève la colonne tag, et j'ajoute baliseCorrection et la réponse
		Schema::table('questions', function($t) 
		{
			$t->dropColumn('tag');
			$t->text('baliseCorrection');
			$t->text('reponse');
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('questions', function($t) 
		{
			$t->dropColumn('baliseCorrection');
			$t->dropColumn('reponse');
			$t->string('tag');
		});
	}

}
