@if ($lignes->isEmpty())
	<p>Aucune question de disponible!</p>
@else
<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th class="text-left">Nom</th>
				<th class="text-left">Enoncé</th>
				<th class="text-left">Sur</th>
				<th> </th>
			</tr>
		</thead>
		<tbody>
		@foreach($lignes as $question)
			<tr>
				<td><a href="{!! action('QuestionsController@show', [$question->id]) !!}">{{ $question->nom }}</a></td>
				<td><div id="enonce" class="resizeDiv resizeDiv-height-2-rows" >{!!$question->enonce!!}</div></td>
				<td>{{ $question->sur }} </td>
				<td><a href="{!! action('QuestionsController@edit', [$question->id]) !!}" class="btn btn-info">Éditer</a></td>
				<td>
				{!! Form::open(array('action' => array('QuestionsController@destroy', $question->id), 'method' => 'delete', 'data-confirm' => 'Êtes-vous certain?')) !!}
					<button type="submit" href="{!! URL::route('questions.destroy', $question->id) !!}" class="btn btn-danger btn-mini">Effacer</button>
				{!! Form::close() !!}   
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>

@endif