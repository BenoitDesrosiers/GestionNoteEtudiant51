<?php if(!isset($classe)) {$classe = new Classe;}?>

<div class="form-group">
	{!! Form::label('code', 'Code:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::text('code',$classe->code, ['class' => 'form-control']) !!}
		{!! $errors->first('code') !!}		
	</div>
</div>

<div class="form-group">
	{!! Form::label('nom', 'Nom:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::text('nom',$classe->nom, ['class' => 'form-control']) !!}
		{!! $errors->first('nom') !!}
	</div>
</div>

<div class="form-group">

	{!! Form::label('sessionscholaire_id', 'Session:', ['class' => "col-sm-2 control-label"]) !!} 
	@if(isset($sessionsList))
	<div class = 'col-sm-10'>
		{!! Form::select('sessionscholaire_id', $sessionsList,$sessionSelected, array('id' => 'sessionscholaire_id', 'size' =>1, 'class' => 'form-control')) !!}
	
		{{-- Form::text('sessionscholaire_id',$classe->session, ['class' => 'form-control']) --}}
		{!! $errors->first('sessionscholaire_id') !!}	
	</div>
	@else
	<div class = 'col-sm-10'>
		{!! Form::text('nom',$classe->sessionscholaire->nom, ['class' => 'form-control']) !!}
	</div>
	@endif
</div>

<div class="form-group">
	{!! Form::label('groupe', 'Groupe:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::text('groupe',$classe->groupe, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('local', 'Local:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::text('local',$classe->local, ['class' => 'form-control']) !!}
	</div>	
</div>	