<!DOCTYPE html>
<html lang="fr">
<head>
test
	<meta charset="UTF-8">
	<title>Gestion des notes</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
	{{ HTML::script('assets/js/script.js') }}
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<h1>Une erreur c'est produite.</h1>
{{ link_to(action('HomeController@index'), 'Page principale') }}
</body>



</html>