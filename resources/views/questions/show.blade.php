@extends('layout')
@section('content')

<section class="header section-padding">
	<div class="container">
		<div class="header-text">
			<h1>Affichage</h1>
			<p>Affichage d'une question</p>
		</div>
	</div>
</section>
<div class="container">
	<section class="section-padding">
		<div class="jumbotron text-left">
			@include('questions.editForm')
		</div>
		
		<a href="{{ action('QuestionsController@index') }}" class="btn btn-info">Retour aux Questions</a>
	</section>
</div>
@stop