@extends('layout')
@section('content')
	<div class="container-fluid">
		<section class="section-padding">
			<div class="jumbotron text-left">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h1> Liste des classes disponibles</h1>
						<a href="{!!action('ClassesController@create') !!}" class="btn btn-primary">Créer une classe</a>						
					</div>
					
					@if ($lignes->isEmpty())
						<p>Aucune classes disponible!</p>
					@else
					
						
					<div class="table-responsive">	{{-- voir http://getbootstrap.com/css/#tables-responsive --}}
						<table class="table">
							<thead>
								<tr>
									<th>Code</th>
									<th>Nom</th>
									<th>Session</th>
									<th>Groupe</th>
									<th>Local</th>
									<th> </th>
									<th> </th>
									<th> </th>
									<th> </th>
									<th> </th>
									
								</tr>
							</thead>
							<tbody>
								
								@foreach($lignes as $classe)
									<tr>
										<td><a href="{!! action('ClassesController@show', $classe->id) !!}">{{ $classe->code }}</a> </td>
										<td>{{ $classe->nom }} </td>
										<td>{{ $classe->sessionscholaire()->first()->nom }} </td>
										<td>{{ $classe->groupe }} </td>
										<td>{{ $classe->local }} </td>
										<td><a href="{!! action('ClassesController@edit',$classe->id) !!}" class="btn btn-info">Éditer</a></td>
										<td>
											{!! Form::open(array('action' => array('ClassesController@destroy',$classe->id), 'method' => 'delete', 'data-confirm' => 'Êtes-vous certain?')) !!}
	                                        	<button type="submit" href="{!! URL::route('classes.destroy', $classe->id) !!}" class="btn btn-danger btn-mini">Effacer</button>
	                                        {!! Form::close() !!}   {{-- méthode pour faire le delete tel que décrit sur http://www.codeforest.net/laravel-4-tutorial-part-2 , 
	                                        						   un script js est appelé pour tous les form qui ont un "data-confirm" (voir assets/js/script.js) --}}
										</td>
										<td><a href="{!! action('TPsController@index',array('belongsToId'=>$classe->id)) !!}" class="btn btn-info">TPs</a></td>
										<td><a href="{!! action('EtudiantsController@index',array('belongsToId'=>$classe->id)) !!}" class="btn btn-info">Étudiants</a></td>
										
									</tr>
								@endforeach
							</tbody>
								
						</table>
					</div>
					@endif
				</div>
			</div>
		</section>
	</div>

@stop