@extends('layout')
@section('content')

<section class="header section-padding">
	<div class="container">
		<div class="header-text">
			<h1>Résultats de {{{$tp->nom}}} pour la classe {{{$classe->nom}}} </h1>
		</div>
	</div>
</section>

<div class="container">
	<section class="section-padding">
		<div class="jumbotron text-left">
			<div class = "table-responsive">
				<table class="table table-hover" >
					<thead>
						<th>Nom</th>
					<?php $nombreQuestion = count($resultats[1]['notes']);
							$i = 1;
							$total_tp = 0;
							$total_par_question = [];
							$grand_total= 0;?>
					@foreach($questions as $question)
						<?php $total_tp += $question->pivot->sur_local; 
							  $total_par_question[$i] = 0;?>
						<th>Q{{$i++}} / {{$question->pivot->sur_local}}</th>
					@endforeach
						<th>sur {{$total_tp}}</th>
					</thead>
					
					<tbody>
					
					@foreach($resultats as $resultat)
						<tr>
							<?php $i = 1;
								  $total_etudiant = 0;?>
							<td>{{{$resultat['nom']}}}</td>
							@foreach($resultat['notes'] as $note)
							<?php $total_etudiant += $note->note;	
								  $total_par_question[$i++]+= $note->note;
								  $couleur = $note->reponse == ""?"danger":"success";
							?>
							<td  class={{$couleur}}>{{{$note->note}}}</td>
							@endforeach
							<?php $grand_total += $total_etudiant;?>
							<td>{{$total_etudiant}}</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<td>Total:</td>
							<?php $i=1;?>
							@foreach($questions as $question)
							<td>{{number_format($total_par_question[$i++] /$question->pivot->sur_local / count($resultats)*100, 2) }}%</td>
							@endforeach
							<td>{{number_format($grand_total / $total_tp / count($resultats)*100, 2)}}%</td>
						</tr>	
					</tfoot>
				</table>
					
			
			</div>
		
				
		</div>
	</section>
</div>
@stop