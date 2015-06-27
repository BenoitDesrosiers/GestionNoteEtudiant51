@extends('layout')
@section('content')

<section class="header section-padding">
	<div class="container">
		<div class="header-text">
			<h1>Affichage d'une classe</h1>
		</div>
	</div>
</section>
<div class="container">
	<section class="section-padding">
		<div class="jumbotron text-left">
			<div class="form-horizontal">			
				@include('classes.editForm')  <?php //TODO: l'include est un formulaire, et les champs sont editable... c'est pas clean ?>
			</div>	
			{{ link_to(URL::previous(), 'Écran précédent', ['class'=>"btn btn-info"]) }}				
		</div>
	</section>
</div>
@stop