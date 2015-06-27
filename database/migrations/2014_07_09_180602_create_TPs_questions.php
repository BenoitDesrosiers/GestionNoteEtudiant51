<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTPsQuestions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tps_questions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tp_id');
			$table->integer('question_id');
			$table->integer('sur_local');
			$table->integer('ordre');
			$table->timestamps();
		});	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tps_questions');
	}

}
