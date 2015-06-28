<!-- resources/views/auth/register.blade.php -->

<form method="POST" action="/auth/register">
    {!! csrf_field() !!}

    <div class="col-md-6">
        DA ou numéro d'employé
        <input type="text" name="name" value="{{ old('name') }}">
    </div>

    <div>
        Courriel
        <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
        Mot de passe
        <input type="password" name="password">
    </div>

    <div class="col-md-6">
        Confirmation du mot de passe
        <input type="password" name="password_confirmation">
    </div>
    
    <div class="col-md-6">
        Prénom
        <input type="text" name="prenom" value="{{ old('prenom') }}">
    </div>
    
     <div class="col-md-6">
        Nom
        <input type="text" name="nom" value="{{ old('nom') }}">
    </div>
    
    <div class="col-md-6">
        Type (e = étudiant, p = professeur)
        <input type="text" name="type" value="{{ old('type') }}">
    </div>
    
    <div>
        <button type="submit">Register</button>
    </div>
</form>
