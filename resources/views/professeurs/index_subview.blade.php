@if ($lignes->isEmpty())
	<p>Aucun professeur de disponible!</p>
@else
<div class="table-responsive">	{{-- voir http://getbootstrap.com/css/#tables-responsive --}}
	<table class="table">
		<thead>
			<tr>
				<th>Nom</th>
				<th># employé</th>
				<th> </th>
			</tr>
		</thead>
		<tbody>
		@foreach($lignes as $user)
		<tr>
			<td><a href="{{ action('ProfesseursController@show', [$user->id]) }}">{{ $user->prenom.' '.$user->nom }} </a></td>
			<td>{{ $user->name }} </td>
			<td><a href="{{ action('ProfesseursController@edit', [$user->id]) }}" class="btn btn-info">Éditer</a></td>
		</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endif
