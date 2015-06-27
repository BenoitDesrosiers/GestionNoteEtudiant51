<?php
use App\Models\Question;
if(!isset($question)) {$question = new Question;}
?>
<div id="belongsToSelect" class="form-group">
	{!!Form::label('dummy', "Associer à:", ['class' =>"col-sm-2 control-label"])!!}
	<div class = 'col-sm-7'>
		{!! Form::select('belongsToListSelect[]', $belongsToList,$belongsToSelectedIds, 
						['id' => 'belongsToListSelect', 'size' =>5, 'multiple'=>'true',
						'class' => 'form-control']) !!}
	</div>
	<div  class = 'col-sm-3 form-group'>
		{!!Form::label('dummy', "Filtre de Classe", ['class' =>"col-sm-12 control-label"])!!}
		<div id="filtre1" class = 'col-sm-12'>
			{!! Form::select('filtre1Select', $filtre1["selectList"], 0, ['class' =>"form-control", 'id' => 'filtre1Select']) !!}
		</div>
	</div>
</div> <!-- belongsToSelect -->

<div class="form-group">
	{!! Form::label('nom', 'Nom:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::text('nom', $question->nom, ['class' => 'form-control']) !!}
		{{ $errors->first('nom') }}
	</div>
</div>
<div class="form-group">
	{!! Form::label('enonce', 'Énoncé:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::textarea('enonce', $question->enonce, ['class' => 'form-control ckeditor']) !!}
		{{ $errors->first('enonce') }}
	</div>
</div>
<div class="form-group">
	{!! Form::label('baliseCorrection', 'Balises de correction:', ['class' => "col-sm-2 control-label"]) !!} 	
	<div class = 'col-sm-10'>
		{!! Form::textarea('baliseCorrection', $question->baliseCorrection, ['class' => 'form-control ckeditor']) !!}
		{{ $errors->first('baliseCorrection') }}
	</div>
</div>
<div class="form-group">
	{!! Form::label('reponse', 'Réponse:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::textarea('reponse', $question->reponse, ['class' => 'form-control ckeditor']) !!}
		{{ $errors->first('reponse') }}
	</div>
</div>
<div class="form-group">
	{!! Form::label('sur', 'Sur:', ['class' => "col-sm-2 control-label"]) !!} 
	<div class = 'col-sm-10'>
		{!! Form::text('sur', $question->sur, ['class' => 'col-sm-1 form-control']) !!}
		{{ $errors->first('sur') }}
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
