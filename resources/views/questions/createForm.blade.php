<div class="form-group">
	{{ Form::label('nom', 'Nom:') }} 
	{{ Form::text('nom',null, ['class' => 'form-control']) }}
	{{ $errors->first('nom') }}
</div>
<div class="form-group">
	{{ Form::label('enonce', 'Énoncé:') }} 
	{{ Form::textarea('enonce',null, ['class' => 'form-control']) }}
	{{ $errors->first('enonce') }}
</div><div class="form-group">
	{{ Form::label('baliseCorrection', 'Balises:') }} 
	{{ Form::textarea('baliseCorrection',null, ['class' => 'form-control']) }}
	{{ $errors->first('baliseCorrection') }}
</div>
<div class="form-group">
	{{ Form::label('reponse', 'Réponse:') }} 
	{{ Form::textarea('reponse',null, ['class' => 'form-control']) }}
	{{ $errors->first('reponse') }}
</div>
<div class="form-group">
	{{ Form::label('sur', 'Sur:') }} 
	{{ Form::text('sur',null, ['class' => 'form-control']) }}
	{{ $errors->first('sur') }}	
</div>