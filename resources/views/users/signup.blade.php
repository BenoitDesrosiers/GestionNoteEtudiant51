@extends('layout')
@section('content')
        <div class="maincontent">
            <h1>Enregistrement</h1>
            {{-- j'ai copié le code de zizaco/confide/src/views/generators car je le perdai lors du push sur github --}}
            <form method="POST" action="{{{ URL::to('users') }}}" accept-charset="UTF-8">
			    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
			    <fieldset>
			        <div class="form-group">
			            <label for="username">{{{ Lang::get('confide::confide.username') }}}</label>
			            <input class="form-control" placeholder="{{{ Lang::get('confide::confide.username') }}}" type="text" name="username" id="username" value="{{{ Input::old('username') }}}">
			        </div>
			        <div class="form-group">
			            <label for="prenom">{{{ 'Prénom' }}}</label>
			            <input class="form-control" placeholder="{{{ 'prenom' }}}" type="text" name="prenom" id="prenom" value="{{{ Input::old('prenom') }}}">
			        </div>
			        <div class="form-group">
			            <label for="nom">{{{ 'Nom' }}}</label>
			            <input class="form-control" placeholder="{{{ 'Nom' }}}" type="text" name="nom" id="nom" value="{{{ Input::old('nom') }}}">
			        </div>
			         <div class="form-group">
			            <label for="type">{{{ 'Type' }}}</label>
			            <input class="form-control" placeholder="{{{ 'Type' }}}" type="text" name="type" id="type" value="{{{ Input::old('type') }}}">
			        </div>
			        <div class="form-group">
			            <label for="email">{{{ Lang::get('confide::confide.e_mail') }}} <small>{{ Lang::get('confide::confide.signup.confirmation_required') }}</small></label>
			            <input class="form-control" placeholder="{{{ Lang::get('confide::confide.e_mail') }}}" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
			        </div>
			        <div class="form-group">
			            <label for="password">{{{ Lang::get('confide::confide.password') }}}</label>
			            <input class="form-control" placeholder="{{{ Lang::get('confide::confide.password') }}}" type="password" name="password" id="password">
			        </div>
			        <div class="form-group">
			            <label for="password_confirmation">{{{ Lang::get('confide::confide.password_confirmation') }}}</label>
			            <input class="form-control" placeholder="{{{ Lang::get('confide::confide.password_confirmation') }}}" type="password" name="password_confirmation" id="password_confirmation">
			        </div>
			
			        @if (Session::get('error'))
			            <div class="alert alert-error alert-danger">
			                @if (is_array(Session::get('error')))
			                    {{ head(Session::get('error')) }}
			                @endif
			            </div>
			        @endif
			
			        @if (Session::get('notice'))
			            <div class="alert">{{ Session::get('notice') }}</div>
			        @endif
			
			        <div class="form-actions form-group">
			          <button type="submit" class="btn btn-primary">{{{ Lang::get('confide::confide.signup.submit') }}}</button>
			        </div>
			
			    </fieldset>
			</form>
        </div>
@stop