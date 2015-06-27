@extends('layout')
@section('content')



	<div class="container-fluid">
		<section class="section-padding">
			<div class="jumbotron text-left">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h1> Liste des classes disponibles</h1>
						<a href="{{ action('ClassesController@create') }}" class="btn btn-info">Créer une classe</a>						
					</div>
					
					@if ($classes->isEmpty())
						<p>Aucune classes disponible!</p>
					@else
							{{-- entête de la table --}}
						<div class="row">
							<div class="col-xs-2 col-md-2 "><small><strong>Code</strong></small></div>
							<div class="col-xs-8 col-md-3"><small><strong>Nom</strong></small></div>
							<div class="col-xs-2 col-md-1"><small><strong>Session</strong></small></div>
							<div class="col-xs-2 col-md-1"><small><strong>Groupe</strong></small></div>
							<div class="col-xs-2 col-md-1"><small><strong>Local</strong></small></div>
							<div class="col-xs-8 col-md-4">  </div>
						</div>
						{{-- ligne de la table --}}
						@foreach($classes as $classe)
						<div class="row">
							<div class="col-xs-2 col-md-2"><a href="{{ action('ClassesController@show', $classe->id) }}">{{ $classe->code }}</a> </div>
							<div class="col-xs-8 col-md-3">{{ $classe->nom }}</div>
							<div class="col-xs-2 col-md-1">{{ $classe->session }}</div>
							<div class="col-xs-2 col-md-1">{{ $classe->groupe }}</div>
							<div class="col-xs-2 col-md-1">{{ $classe->local }}</div>
							<div class="col-xs-8 col-md-4">  
								<div class="col-xs-3 col-md-3"><a href="{{ action('ClassesController@edit',$classe->id) }}" class="btn btn-info">Éditer</a> </div>
								<div class="col-xs-3 col-md-3">
									{{ Form::open(array('action' => array('ClassesController@destroy',$classe->id), 'method' => 'delete', 'data-confirm' => 'Êtes-vous certain?')) }}
	                                	<button type="submit" href="{{ URL::route('classes.destroy', $classe->id) }}" class="btn btn-danger btn-mini">Effacer</button>
	                                {{ Form::close() }}   {{-- méthode pour faire le delete tel que décrit sur http://www.codeforest.net/laravel-4-tutorial-part-2 , 
	                                        						   un script js est appelé pour tous les form qui ont un "data-confirm" (voir assets/js/script.js) --}}
								</div>
								<div class="col-xs-3 col-md-3"><a href="{{ action('TPsController@index',array('belongsToId'=>$classe->id))}}" class="btn btn-info">TPs</a> </div>
								<div class="col-xs-3 col-md-3"><a href="{{ action('ClassesEtudiantsController@index',$classe->id) }}" class="btn btn-info">Étudiants</a></div>
							</div>
						</div>
						<div><hr></div>
						@endforeach
					@endif
				</div> {{-- panel default --}}
			</div> {{-- jumbotron --}}
		</section>
	</div> {{-- container --}}

@stop