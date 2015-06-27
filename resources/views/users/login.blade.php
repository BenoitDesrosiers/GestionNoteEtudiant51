@extends('layout')
@section('content')
        <div class="maincontent">
            <h1>Enregistrement</h1>
            {{-- Renders the signup form of Confide --}}
            {{ Confide::makeLoginForm()->render(); }}
        </div>
@stop