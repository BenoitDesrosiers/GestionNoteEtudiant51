@if(empty($lignes))
	<p>Aucun travail pratique disponible!</p>
@else
	<div class="table-responsive">	{{-- voir http://getbootstrap.com/css/#tables-responsive --}}
		<table class="table">
			<thead>
				<tr>
					<th>Classe</th>
					<th>Nom</th>
					<th class="text-right">Sur</th>
					<th class="text-right" >% de la note finale</th>
					<th> </th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php 	
					$total_sur = 0; 
					$poids = 0; 
					$poids_local = 0; 
					$ancienNomClasse = "";
				?> 
				
				@foreach($lignes as $classeNom => $tps)
					@foreach($tps as $tp)				
						<?php 
							$poids += $tp->poids;
							$poids_local += $tp->pivot->poids_local; 
							$unTotalSur = $tp->questions()->sum('sur_local');
							$total_sur += $unTotalSur;
						?> 	
									
					<tr @if($ancienNomClasse <> $classeNom) {{'class ="success"'}} @endif>
						<td>@if($ancienNomClasse <> $classeNom) {{{$classeNom}}} @endif</td>
						<td><a href="{{ action('TPsController@show', [$tp->id]) }}">{{ $tp->nom }}</a> </td>
						<td class="text-right">{{ $tp->questions()->sum('sur_local')}} </td>
						<td class="text-right">{{ $tp->pivot->poids_local }} </td>
						<td>@if(!$tp->pivot->corrige)<a href="{{ route('tpsPassation.repondre', [$tp->pivot->classe_id, $tp->id]) }}" class="btn btn-info">Répondre</a>@endif</td>
						<td>@if($tp->pivot->corrige)
								<a href="{{ route('tpsPassation.voirCorrection', [$tp->pivot->classe_id, $tp->id]) }}" class="btn btn-info">Correction</a></td>
							@endif
						<td>					
												
					</tr>
					<?php $ancienNomClasse = $classeNom; ?>
					
					@endforeach
				@endforeach
			</tbody>
		</table>
	</div>
@endif