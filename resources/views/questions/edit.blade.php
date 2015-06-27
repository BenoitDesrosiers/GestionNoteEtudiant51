@extends('layout')
@section('content')

<section class="header section-padding">
	<div class="container">
		<div class="header-text">
			<h1>Édition d'une question</h1>
		</div>
	</div>
</section>

<div class="container">
	<section class="section-padding">
		<div class="jumbotron text-left">
			{!! Form::open(['action'=> array('QuestionsController@update', $question->id), 'method' => 'PUT', 'class' => 'form-horizontal form-compact', 'role'=>'form']) !!}
				@include('questions.editForm')
				<div class="form-group">
					{!! Form::submit('Sauvegarder', ['class' => 'btn btn-primary']) !!}
				</div>
			{!! Form::close() !!}
		</div>
	</section>
</div>
@stop