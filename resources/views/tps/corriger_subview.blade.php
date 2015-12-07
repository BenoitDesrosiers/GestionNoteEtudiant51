

<div class="form-group">

	<div class = 'col-sm-12 col-md-6 col-lg-4'>
		<div id="enonce" class="resizeDiv resizeDiv-height-2-rows" ">
			{!!$question->enonce!!}
		</div>
	</div>
	@if(strlen($question->baliseCorrection) > 0)
	<div class = 'col-sm-12 col-md-6 col-lg-4'>
		<div id="questionBaliseCorrection" class="resizeDiv resizeDiv-height-2-rows">
			<strong>Balises de correction:</strong><br> {!!$question->baliseCorrection!!}
		</div>
	</div>
	@endif
	@if(strlen($question->reponse) > 0)
	<div class = 'col-sm-12 col-md-12 col-lg-4'>
		<div id="questionReponse" class="resizeDiv resizeDiv-height-2-rows">
			<strong>Réponse:</strong><br>{!!$question->reponse!!}
		</div>
	</div>
	@endif
	
</div>
<div class="form-group">
	<div class="col-sm-12"><strong>Réponse de l'étudiant</strong></div>	
	<div class = 'col-sm-12'>
		<?php if(strlen($reponse->reponse) == 0) 
			     {$lareponse = "<mark>Aucune réponse donnée</mark>";
			  } else {$lareponse = $reponse->reponse;} ?>
		<div id="reponse" class="resizeDiv"> {!!$lareponse!!}</div>
	</div>
</div>
<div class="form-group">
	<div class = 'col-xs-2'>
		{!! Form::label('pointage', 'Points:') !!} 	
	</div>
	<div class = 'col-xs-2'>
		{!! Form::text('pointage', $reponse->note, ['class' => 'form-control']) !!}
	</div>
	<div class = 'col-xs-2'>
		{!! Form::label('pointage', '/'.$question->pivot->sur_local) !!} 	
	</div>
		
</div>

<div class="form-group">
	<div class='col-sm-12'>
		<strong>Commentaires de correction</strong>
	</div>
	<div class = 'col-sm-12'>
		{!! Form::textarea('commentaire', $reponse->commentaire, ['class' => 'form-control ckeditor', 'rows' => '4']) !!}
	</div>
	
	
</div>

<div class="form-group">
	<div class = 'col-sm-12'>
		{!! Form::label('label_commentaire_visible', 'Afficher ce commentaire immédiatement', ['class' => ""]) !!} 
		{!!Form::checkbox("commentaire_visible",$reponse->commentaire_visible,($reponse->commentaire_visible==0?'':'checked'))!!}
	</div>
</div>
