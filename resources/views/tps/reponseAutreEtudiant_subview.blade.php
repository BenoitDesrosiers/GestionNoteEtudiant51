<div>
	<div class="col-xs-12"><strong>Corrections déjà faites pour les autres étudiants</strong></div> 
	
	<div class="form-group">
		<div class="col-sm-9"><strong>{{'Réponse de: '.$nom."&nbsp;&nbsp;&nbsp;valant ".$pointage.' points'}}</strong></div>
		<div class = 'col-sm-12'>
			<?php if(strlen($reponse) == 0) 
			     {$reponse = "<mark>Aucune réponse donnée</mark>";
			  } ?>
			<div id="reponse1" class="resizeDiv"> {{$reponse}}</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-12"><strong>'Commentaires de correction</strong></div>	
		<div class = 'col-sm-12'>
			<?php if(strlen($commentaire) == 0) 
			     {$commentaire = "<mark>Aucun commentaire donné</mark>";
			  } ?>
			<div id="commentaire1" class="resizeDiv"> {{$commentaire}}</div>
		</div>
	</div>
	
	<?php if($flagBoutonEtudiantPrecedent) {$disabledState = ''; } else {$disabledState = 'disabled';} ?>
		{{ Form::submit('étudiant précédent', ['class' => 'btn btn-primary', 'name'=>'etudiantPrecedent', $disabledState=>$disabledState, 
				"onclick" => "afficheAutreEtudiant('precedent')"]) }}
	<?php if($flagBoutonEtudiantSuivant) {$disabledState = ''; } else {$disabledState = 'disabled';} ?>
		{{ Form::submit('étudiant suivant', ['class' => 'btn btn-primary', 'name'=>'etudiantSuivant', $disabledState=>$disabledState,
				"onclick" => "afficheAutreEtudiant('suivant')"]) }}
</div>