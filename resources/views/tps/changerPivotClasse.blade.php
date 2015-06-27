@extends('layout')
@section('content')

<section class="header section-padding">
	<div class="container">
		<div class="header-text">
			<h1>Edition du TP &quot;{{{$tp->nom}}}&quot; pour la classe &quot;{{{$classe->nom}}}&quot;</h1>
		</div>
	</div>
</section>

<div class="container">
	<section class="section-padding">
		<div class="jumbotron text-left">
			{!! Form::open(['action'=> array('TPsController@doChangerPivotClasse', $tp->id, $classe->id), 'method' => 'PUT', 'class' => 'form-horizontal form-compact', 'role'=>'form']) !!}
				<div class="form-group">
					{!! Form::label('poids_local', 'Poids local:', ['class' => "col-sm-2 control-label"]) !!} 
					<div class = 'col-sm-10'>
						{!! Form::text('poids_local',$tp->pivot->poids_local, ['class' => 'form-control']) !!}
						{{$errors->first('poids_local')}}
					</div>
				</div>
				<div class="form-group">
					<div class ='col-sm-4'>
						{!! Form::label('label_commentaire_visible', 'Est-ce que les commentaires de correction sont visibles lors de la passation', ['class' => "control-label"]) !!} 
					</div>
					<div class = 'col-sm-1'>
							{!!Form::checkbox("commentaire_visible",$tp->pivot->commentaire_visible,($tp->pivot->commentaire_visible==0?'':'checked'))!!}</td>
					</div>
				</div>
				<div class="form-group">
					{!! Form::submit('Sauvegarder', ['class' => 'btn btn-primary']) !!}
				</div>
			{!! Form::close() !!}
		</div>
	</section>
</div>
@stop