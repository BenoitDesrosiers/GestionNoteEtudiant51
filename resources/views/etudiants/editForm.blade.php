<?php if(!isset($etudiant)) {$etudiant = new Etudiant;}?>
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
	{!! Form::label('nom', 'Nom:', ['class' => "col-sm-2 text-right"]) !!} 
	{!! Form::label('nom1', $etudiant->prenom.' '.$etudiant->nom, ['class' => "col-sm-10  text-left"]) !!} 
</div>
<div class="form-group">
	{!! Form::label('da', 'DA:', ['class' => "col-sm-2 text-right"]) !!} 
	{!! Form::label('da1', $etudiant->name, ['class' => "col-sm-10  text-left"]) !!} 
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