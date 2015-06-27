@if (empty($lignes))
	<p>Aucun travail pratique disponible!</p>
@else
	<div class="table-responsive">	{{-- voir http://getbootstrap.com/css/#tables-responsive --}}
		<table class="table">
			<thead>
				<tr>
					<th>Classe</th>
					<th>Nom</th>
					<th class="text-right">Sur (calculé)</th>
					<th class="text-right">Poids</th>
					<th> </th>
				</tr>
			</thead>
			<tbody>
				<?php 	
					$total_sur = 0; 
					$poids_local = 0; 
					$ancienNomClasse = "";
				?> 
				@foreach($lignes as $classeNom => $tps)
				@foreach($tps as $tp)
				
						<?php 
							if(!$tp->classes->isempty()) {$poids_local += $tp->pivot->poids_local;}								
							$unTotalSur = $tp->questions()->sum('sur_local');
							$total_sur += $unTotalSur;
						?> 	
									
					<tr @if($ancienNomClasse <> $classeNom) {{'class ="success"'}} @endif>
						<td>@if($ancienNomClasse <> $classeNom) {{{$classeNom}}} @endif</td>
					
						<td><a href="{!! action('TPsController@show', [$tp->id]) !!}">{{ $tp->nom }}</a> </td>
						<td class="text-right">{{ $tp->questions()->sum('sur_local')}} </td>
						<td>@if(!$tp->classes->isempty()) <a href="{!! route('tps.changerPivotClasse',  [$tp->id, $tp->pivot->classe_id]) !!}" class="btn btn-info">{{$tp->pivot->poids_local}}</a>@endif</td>						
						
						
						
						<td><a href="{!! action('TPsController@edit', [$tp->id]) !!}" class="btn btn-info">Éditer</a></td>
						<td>
						{!! Form::open(array('action' => array('TPsController@destroy', $tp->id), 'method' => 'delete', 'data-confirm' => 'Êtes-vous certain?')) !!}
							<button type="submit" href="{!! route('tps.destroy', $tp->id) !!}" class="btn btn-danger btn-mini">Effacer</button>
						{!! Form::close() !!}
						</td>
						<td><a href="{!! action('QuestionsController@index', ['belongsToId' => $tp->id]) !!}" class="btn btn-info">Questions</a></td>	
						<td><a href="{!! route('tps.format', [$tp->id]) !!}" class="btn btn-info">Format</a></td>	
											
						<td>@if(!$tp->classes->isempty())<a href="{!! route('tps.distribuer',  [$tp->id]) !!}" class="btn btn-info">Distribuer</a>@endif</td>						
						<?php $tp_distribue = false;
								if($tp->pivot){ //si le TP n'est pas associé à une classe, il n'y aura pas de pivot
									$tp_distribue = $tp->pivot->distribue;
								}
						?>
						<td>@if($tp_distribue) <a href="{!! route('tps.corriger',  [$tp->id, $tp->pivot->classe_id]) !!}" class="btn btn-info">Corriger</a>@endif</td>						
						<td>@if($tp_distribue) <a href="{!! route('tps.afficherResultats',  [$tp->id, $tp->pivot->classe_id]) !!}" class="btn btn-info">Résultats</a>@endif</td>
						<td>@if($tp_distribue)
								@if($tp->pivot->corrige) 
									 <a href="{!! route('tps.retirerCorrection',  [$tp->id, $tp->pivot->classe_id]) !!}" class="btn btn-info">Retirer correction</a>						
								@else 
									 <a href="{!! route('tps.transmettreCorrection',  [$tp->id, $tp->pivot->classe_id]) !!}" class="btn btn-info">Transmettre</a>						
								@endif
						@endif</td>
						<?php //TODO: IMPORTANT   ajouter la classe afin de pouvoir aller chercher notes()->forClasse($classe->id) afin de mettre le bouton seulment pour les classes qui sont distribuées ?>	
					</tr>
					<?php $ancienNomClasse = $classeNom; ?>
				@endforeach	
				<tr>
					<td>total:</td>
					<td class="text-right"> </td>					
					<td class="text-right"> </td>
					<td class="text-right"> {{ $poids_local }}</td>
				</tr>	
				<?php $poids_local = 0;  ?>
				@endforeach
				
			</tbody>
		</table>
	</div>
@endif