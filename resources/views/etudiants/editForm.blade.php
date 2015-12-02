<?php
use App\Models\User;
if(!isset($etudiant)) {$etudiant = new User;}
?>

<div id="belongsToSelect" class="form-group">
	{!!Form::label('dummy', "Associer à:", ['class' =>"col-sm-2 control-label"])!!}
	<div class = 'col-sm-7'>
		{!! Form::select('belongsToListSelect[]', $belongsToList,$belongsToSelectedIds, 
					['id' => 'belongsToListSelect', 'size' =>5, 'multiple'=>'true',
					'class' => 'form-control']) !!}
	</div>
	<div  class = 'col-sm-3 form-group'>
		{!!Form::label('dummy', "Filtre de Sessions", ['class' =>"col-sm-12 control-label"])!!}
		<div id="filtre1" class = 'col-sm-12'>
			{!! Form::select('filtre1Select', $filtre1["selectList"], 0, ['class' =>"form-control", 'id' => 'filtre1Select']) !!}
		</div>
	</div>
</div> <!-- belongsToSelect -->

<div class="form-group"> 
	{!! Form::label('name', 'DA:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::text('name', $etudiant->name, ['class' => 'form-control']) !!}
		{{ $errors->first('name') }}
	</div>
</div>

<div class="form-group">
	{!! Form::label('prenom', 'Prenom:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::text('prenom', $etudiant->prenom, ['class' => 'form-control']) !!}
		{{ $errors->first('prenom') }}
	</div>
</div>

<div class="form-group">
	{!! Form::label('nom', 'Nom:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::text('nom', $etudiant->nom, ['class' => 'form-control']) !!}
		{{ $errors->first('nom') }}
	</div>
</div>

<div class="form-group"> 
	{!! Form::label('email', 'Courriel:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::text('email', $etudiant->email, ['class' => 'form-control']) !!}
		{{ $errors->first('email') }}
	</div>
</div>

<div class="form-group">
	{!! Form::label('password', 'Mot de passe:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::text('password', $etudiant->password, ['class' => 'form-control']) !!}
		{{ $errors->first('password') }}
	</div>
</div>

<div class="form-group">
	{!! Form::label('programme_id', 'Programme:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::text('programme_id', $etudiant->programme_id, ['class' => 'form-control']) !!}
		{{ $errors->first('programme_id') }}
	</div>
</div>



<script>
$("#filtre1Select").change(function(e) {
	var cat = [];
	<?php //TODO: ce code se répète dans index.blade.php. Comment le mettre en fonction?
		foreach($filtre1["groupes"] as $nomCategorie => $categorieItems) {
			echo "cat['".$nomCategorie. "'] = [";
			foreach($categorieItems as $items) {
				echo $items.", ";
			}
			echo "];\n";
		} 
	?>
	changeSelect("belongsToListSelect", cat[ document.getElementById("filtre1Select").value ], false);
	
});
</script>
{!! HTML::script('assets/js/script_ajax.js') !!}