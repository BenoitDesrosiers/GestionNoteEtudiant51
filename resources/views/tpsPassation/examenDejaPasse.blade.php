@extends('layout')
@section('content')

<section class="header section-padding">
	<div class="container">
		<div class="header-text">
			<h1>{{{$etudiant->prenom}}} {{{$etudiant->nom}}}</h1>
			<h2>{{{$classe->nom}}} / {{{$tp->nom}}}</h2>
			<p>sur: {{$tp->questions()->sum('sur_local') }}/ &nbsp;&nbsp;vaut: {{{$tp->pivot->poids_local}}}% de la note finale</p>
		</div>
	</div>
</section>

<div class="container">
	<section class="section-padding">
		<div class="jumbotron text-left">
			<div class="alert alert-danger"><strong>Cet examen est corrigé, vous ne pouvez y répondre</strong></div>
		</div>
	</section>
</div>
@stop