<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionscholaires extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sessionscholaires', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nom');
			$table->boolean('courant')->default(false); // la session courante 
														//TODO envoyer ca dans un fichier de config, 
														//   sinon ca va être dur d'assurer qu'il n'y en a qu'une qui est vrai 
			
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
		Schema::drop('sessionscholaires');
	}

}
