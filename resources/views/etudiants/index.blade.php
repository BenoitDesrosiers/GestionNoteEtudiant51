@extends('layout')
@section('content')

	<div class="container">
		<section class="section-padding">
			<div class="jumbotron text-left">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h1> Liste des étudiants</h1>
						{!! Form::open(['action'=> ['EtudiantsController@create'], 'role' => 'form', 'method' => 'get', 'class' => 'form-inline']) !!}
							{!! Form::hidden('belongsToId', '1', array('id'=>'belongsToId')) !!}
							<div class="form-group">
								<div>
									{!! Form::submit('Créer un Étudiant', ['class' => 'btn btn-primary'])!!}
								</div>
							</div>
							<div class="form-group" id="belongsToSelect">
								<div>						
									{!! Form::select('belongsToListSelect', $belongsToList, $belongsToSelectedIds, 
											['id' => 'belongsToListSelect', 'class' => 'form-control']) !!}
								</div>
							</div> <!-- belongsToSelect -->
							<div class="form-group" id="filtre1" >
								<div>
									{!! Form::select('filtre1Select',  $filtre1["selectList"], 0, 
											['id' => 'filtre1Select', 'class' => 'form-control']) !!}
								</div>
							</div>
						{!! Form::close() !!}
					
					<div class="panel-body">
						<div id="liste-items">
							<?php // cette div sera remplie par le code js ?>
						</div> <!-- liste-items -->
					</div>
				</div>
			</div>
		</section>
	</div>
<script>
	var controllerCallBackRoute ='{!!URL::route('etudiantsPourClasse') !!}'

	/*
	 * change le contenu du "belongsToSelect" selon les filtres sélectionnés. 
	 */
	 
	$("#filtre1Select").change(function(e) {
		var cat = [];
		<?php 
			foreach($filtre1["groupes"] as $nomCategorie => $categorieItems) {
				echo "cat['".$nomCategorie. "'] = [";
				foreach($categorieItems as $items) {
					echo $items.", ";
				}
				echo "];\n";
			} 
		?>
		changeSelect("belongsToListSelect", cat[ document.getElementById("filtre1Select").value ], true);
		afficheListeItems();
		updateCreateButton();
		
	});
</script>
{!! HTML::script('assets/js/script_ajax.js') !!}

@stop