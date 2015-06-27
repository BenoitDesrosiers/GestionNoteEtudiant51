@extends('layout')
@section('content')

<section class="header section-padding">
	<div class="container">
		<div class="header-text">
			<h1>Affichage d'un TP</h1>
		</div>
	</div>
</section>
<div class="container">
	<section class="section-padding">
		<div class="jumbotron text-left">
			<div class="form-horizontal">
				@include('tps.editForm')
			</div>
			{{ link_to(Request::header('referer'), 'Écran précédent', ['class'=>"btn btn-info"]) }}		
		</div>	
	</section>
</div>
@stop