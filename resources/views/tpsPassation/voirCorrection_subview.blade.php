<div class='col-xs-12'>
<?php $total = 0;?>
@foreach($toutesLesReponses as $reponse)
	<p class='col-xs-1'>{{$reponse->ordre.') '.$reponse->note}}</p>
	<?php $total += $reponse->note?>
@endforeach
	<p class='col-xs-1'>{{'total: '.$total}}</p>

</div>
<?php $i = $premiereQuestion; ?>

@foreach($reponsesPageCourante as $reponse)
		<?php $laQuestion=$toutesLesQuestions->find($reponse->question_id);?>
		<div class='col-sm-2'><strong>{{$reponse->ordre . ") ".$reponse->note. ' / '.$laQuestion->pivot->sur_local}}</strong></div>	
		<div class='col-sm-10'><strong>{{$laQuestion->nom}}</strong></div> 		
		<div class = 'col-sm-12'>
			<div class="resizeDiv">{!!$laQuestion->enonce!!}</div>
		</div>
		<div class='col-sm-12'><strong>Bonne réponse</strong></div>
		<div class = 'col-sm-12'>
			<div class="resizeDiv" ">{!!$laQuestion->reponse?:"."!!}</div>
		</div>
		<div class='col-sm-12'><strong>Ta réponse</strong></div>
		<div class = 'col-sm-12'>
			<div class="resizeDiv" ">{!!$reponse->reponse?:"Aucune réponse soumise"!!}</div>
		</div>
		<div class='col-sm-12'><strong>Commentaires de correction</strong></div>
		<div class = 'col-sm-12'>
			<div class="resizeDiv" ">{!!$reponse->commentaire?:"."!!}</div>
		</div>
		<div class ='col-sm-12' style="background-color: black; height: 5px;"></div>
<?php $i++; ?>
@endforeach

