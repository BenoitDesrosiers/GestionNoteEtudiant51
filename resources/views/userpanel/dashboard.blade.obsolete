<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User auth with Confide</title>
    {{-- Imports twitter bootstrap and set some styling --}}
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #EEE; }
        .maincontent {
            background-color: #FFF;
            margin: 30px auto;
            padding: 20px;
            width: 300px;
            box-shadow: 0 0 20px #AAA;
        }
    </style>
</head>
<body>
    <div class="maincontent">
        <h1>{{ Confide::user()->username }}</h1>
        <div class="well">
            <b>email:</b> {{ Confide::user()->email }}
        </div>
     </div>
</body>
</html>