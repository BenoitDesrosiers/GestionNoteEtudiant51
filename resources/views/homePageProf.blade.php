@extends('layout')
@section('content')
	
	<div class="container-fluid">
		<section class="section-padding">
			<div class="jumbotron text-left">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h1> Bienvenue dans le système de gestion de classes</h1>
						<a href="{{ action('ClassesController@index') }}" class="btn btn-info">Gestion des classes</a>	
						<a href="{{ action('TPsController@index') }}" class="btn btn-info">Gestion des travaux pratiques (TPs)</a>						
						<a href="{{ action('QuestionsController@index') }}" class="btn btn-info">Gestion des questions</a>						
						<a href="{{ action('EtudiantsController@index') }}" class="btn btn-info">Gestion des étudiants</a>
						<a href="{{ route('tpsPassation.index') }}" class="btn btn-info">Tester des travaux</a>	
												
					</div>
				</div>
			</div>
		</section>
	</div>
	
@stop