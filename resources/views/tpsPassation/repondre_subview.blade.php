<div class='col-xs-12'>
<p>
@foreach($toutesLesReponses as $reponse)
	<span class='col-xs-1 label @if($reponse->reponse) label-success @else label-warning @endif '>{{$reponse->ordre}}</span>
@endforeach
</p>
</div>

<?php $i = $premiereQuestion; ?>
@foreach($reponsesPageCourante as $reponse)
	<?php $laQuestion=$toutesLesQuestions->find($reponse->question_id);?>
	<div class='col-sm-10'><strong>{{$i . ") ".$laQuestion->nom}}</strong></div> 	
		
	{!! Form::submit('Sauvegarder', ['class' => 'btn btn-success col-sm-2', 'name' => 'sauvegarde']) !!} {{-- TODO changer ce bouton pour un call ajax, sinon ca revient toujours au top de la page. --}}
	
	<div class = 'col-sm-12'>
		<div id="enonce" class="resizeDiv resizeDiv-height-2-rows" ">{!!$laQuestion->enonce!!}</div>
	</div>
<div class="form-group">
	{!! Form::label('reponse', 'Réponse (sur '.$laQuestion->pivot->sur_local.')', ['class' => "col-sm-12 "]) !!} 	
	<div class = 'col-sm-12'>
		{!! Form::textarea('reponse['.$laQuestion->id.']', $reponse->reponse, ['class' => 'form-control ckEditorBasic', 'rows' => '3']) !!}
	</div>
</div>

@if(($tp->pivot->commentaire_visible or $reponse->commentaire_visible) and $reponse->commentaire)
	<div class='col-sm-12'><strong>Commentaires de correction</strong></div>
		<div class = 'col-sm-12'>
			<div class="resizeDiv" ">{!!$reponse->commentaire?:"."!!}</div>	
		</div>
@endif
<div class ='col-sm-12' style="background-color: black; height: 10px;"></div>

<?php $i++; ?>
@endforeach
<script>
		CKEDITOR.replaceAll('ckEditorBasic',  {
			// Define the toolbar groups as it is a more accessible solution.
			toolbarGroups: [
				{"name":"basicstyles","groups":["basicstyles"]},
				{"name":"paragraph","groups":["list","blocks"]},
				{"name":"document","groups":["mode"]},
				{"name":"insert","groups":["insert"]},
				{"name":"styles","groups":["styles"]},
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Strike,Subscript,Superscript,Anchor,Styles,Specialchar'
		} );
	</script>
