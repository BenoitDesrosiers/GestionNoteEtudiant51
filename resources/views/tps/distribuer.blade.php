@extends('layout')
@section('content')
	<div class="container">
		<section class="section-padding">
			<div class="jumbotron text-left">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h1> Distribution d'un travail pratique</h1>
						<h2>{{{$tp->nom}}}</h2>
					</div> <!-- panel-heading -->
					<div class="panel-body">
						<div id="liste-items"  >
							@if(empty($lignes))
								<p>Ce TP n'est associé à aucune classe et ne peut donc pas être distribué</p>
							@else 
								{!! Form::open(['route'=> ['tps.doDistribuer', $tp->id], 'role' => 'form', 'method' => 'post']) !!}
							
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>Nom de la classe</th>
												<th class="text-right">Session</th>
												<th class="text-center">distribuer</th>
												<th class="text-center">retirer</th>
												
												<th></th>
											</tr>
										</thead>
										<tbody>
										@foreach($lignes as $key => $ligne) 
											<tr>
												<td>{{{$ligne['nom']}}}</td>
												<td class="text-right">{{{$ligne['session']}}}</td>
												<td class="text-center" >
													@if(!$ligne['dejaDistribue']){!!Form::checkbox('distribue[]',$key, true) !!} @endif
													</td>
												<td class="text-center" >@if($ligne['dejaDistribue']){!!Form::checkbox('retire[]',$key)!!}@endif</td>
												
											</tr>
										@endforeach
										
										</tbody>
									</table>								
								</div>
								{!! Form::submit('Distribuer/Retirer les TPs sélectionnés', ['class' => 'btn btn-primary'])!!}
						
								{!! Form::close() !!}	
							@endif
						
						</div> <!-- liste-items -->
					</div>
				</div> <!-- panel -->
			</div> <!-- jumbotron -->
		</section>
	</div>

@stop