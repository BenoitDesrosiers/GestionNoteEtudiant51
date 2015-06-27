@extends('layout')
@section('content')

<section class="header section-padding">
	<div class="container">
		<div class="header-text">
			<h1>Formatage de {{{$tp->nom}}} </h1>
		</div>
	</div>
</section>
<?php 
$instructions =
"Ordre: l'ordre sert à séquencer les questions. Il n'a pas besoin d'être consécutif, seulement ordonné\n
Par exemple: 1, 3, 10, 13 est un bon ordre.\n
Page: cette colonne indique si un changement de page sera fait après cette question pour ce TP. 
"?>
<div class="container">
	<section class="section-padding">
		<div class="jumbotron text-left">
			<div class = 'col-sm-12'>
				<div id="enonce" class="resizeDiv" "><strong>{{$instructions}}</strong></div>
			</div>
			{!! Form::open(['route'=> ['tps.doFormat', $tp->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'role'=>'form']) !!}
				<?php $i = 1;?>
				<div class="form-group">
						<div class = 'col-sm-1'>
							{!! Form::text('ordreT','ordre', ['class' => "form-control",'disabled' => 'disabled']) !!} 	
						</div>
						<div class = 'col-sm-1'>
							{!! Form::text('breakT','page', ['class' => "form-control",'disabled' => 'disabled']) !!} 	
						</div>
						<div class = 'col-sm-3'>
							{!! Form::text('nomquestionT',"nom", ['class' => "form-control",  'disabled' => 'disabled']) !!} 	
						</div>
						<div class = 'col-sm-6'>
							{!! Form::text('enonceT', "Énoncé", ['class' => 'form-control', 'disabled' => 'disabled']) !!}
						</div>
						<div class = 'col-sm-1'>
							{!! Form::text('surT', $tp->questions()->sum('sur_local'), ['class' => 'form-control',  'disabled' => 'disabled']) !!}
						</div>
					</div>
				@foreach($questions as $question)
					<div class="form-group">
						<div class = 'col-sm-1'>
							{!! Form::text("ordre[$question->id]",$i++, ['class' => "form-control"]) !!} 	
						</div>
						<div class = 'col-sm-1'>
							{!!Form::checkbox("break[$question->id]",$question->id,($question->pivot->breakafter==0?'':'checked'))!!}</td>
						</div>
						<div class = 'col-sm-3'>
							{!! Form::text('nomquestion',$question->nom, ['class' => "form-control",  'disabled' => 'disabled']) !!} 	
						</div>
						
						<div class = 'col-sm-6'>
							<div class="resizeDiv resizeDiv-height-2-rows" ">{!!$question->enonce !!}</div>
						</div>
						
						<div class = 'col-sm-1'>
							{!! Form::text('sur', $question->pivot->sur_local, ['class' => 'form-control',  'disabled' => 'disabled']) !!}
						</div>
					</div>
				@endforeach
			
			
				<div class="form-group">
					{!! Form::submit('Sauvegarder', ['class' => 'btn btn-primary', 'name' => 'sauvegarder']) !!}
				</div>
				<div class="form-group">
					{!! Form::submit('Créer une question', ['class' => 'btn btn-primary', 'name' => 'ajoutQuestion']) !!}
				</div>
			{!! Form::close() !!}
		</div>
	</section>
</div>
@stop