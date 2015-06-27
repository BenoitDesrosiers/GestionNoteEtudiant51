@if ($lignes->isEmpty())
	<p>Aucune etudiant de disponible!</p>
@else
<div class="table-responsive">	{{-- voir http://getbootstrap.com/css/#tables-responsive --}}
	<table class="table">
		<thead>
			<tr>
				<th>Nom</th>
				<th>da</th>
				<th> </th>
			</tr>
		</thead>
		<tbody>
		@foreach($lignes as $etudiant)
		<tr>
			<td><a href="{{ action('EtudiantsController@show', [$etudiant->id]) }}">{{ $etudiant->prenom.' '.$etudiant->nom }} </a></td>
			<td>{{ $etudiant->name }} </td>
			<td><a href="{{ action('EtudiantsController@edit', [$etudiant->id]) }}" class="btn btn-info">Éditer</a></td>
		</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endif
