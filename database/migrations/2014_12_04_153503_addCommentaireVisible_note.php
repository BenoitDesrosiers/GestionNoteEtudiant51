<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentaireVisibleNote extends Migration {

	/**
	 * Run the migrations.
	 * 
	 * ajout du flag indiquant qu'on veut que le commentaire de correction soit visible pour cette réponse
	 * @return void
	 */
	public function up()
	{
		Schema::table('notes', function($t)
		{
			$t->boolean('commentaire_visible');
				
		});	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notes', function($t)
		{
			$t->dropColumn('commentaire_visible');
		});
	}

}
