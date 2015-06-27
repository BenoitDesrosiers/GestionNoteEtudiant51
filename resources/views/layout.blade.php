<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Gestion des notes</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- jquery -->
<script src="https://code.jquery.com/jquery.js"></script>

<!-- jquery ui -->
<!--  script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script-->

<!-- bootstrap -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<!-- ckeditor -->
<script src="//cdn.ckeditor.com/4.4.6/full/ckeditor.js"></script>

<!-- jquery ui for resizable -->
<script type="text/javascript"
    src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript"
    src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css"
    href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>

{!! HTML::script('assets/js/script.js') !!}
<link rel="stylesheet" href="{!! asset('css/style.css') !!}">


</head>
<body>
<header>
	<nav class="navbar navbar-default" role="navigation">
		<div class="navbar-header">
	
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-principal">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<p class="navbar-text navbar-left">Cégep de Drummondville</p>
			<p class="navbar-text navbar-left">Bienvenue {!!Auth::user()? Auth::user()->name:'visiteur' !!}</p>
			
			<p> 
		</div>
		<div class="collapse navbar-collapse" id="menu-principal">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="{!! action('HomeController@index') !!}">Écran principal</a></li>
				<li><a href="{!! action('Auth\AuthController@getLogout') !!}">Déconnexion</a></li>
				
				
				
				<!--li><a href="{{-- action('\App\Http\Controllers\Auth\AuthController@getLogout') --}}">logout</a></li-->
				
			</ul>
		</div>
	</nav>
</header>
   	@if(Session::has('message_success')) 
	    <div class="form-group">
	    	<div class="alert alert-success">
	    		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    		{!! Session::get('message_success') !!}
	    	</div>
    	</div>			    	
    @endif
    @if(Session::has('message_danger')) 
	    <div class="form-group">
	    	<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    		{!! Session::get('message_danger') !!}
	    	</div>
    	</div>			    	
    @endif
	


@yield('content')
</body>



</html>
