<?php

namespace App\Http\Controllers\Auth;

use App\Models\User; //c'était juste App\User, mais je l'ai changé pour prendre mon User.php
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255|unique:users',
        	'nom' => 'required|max:255',
        	'prenom' => 'required|max:255',	
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        	'type' => 'required|max:1|in:e,p',
        	'programme_id' => 'required|exists:programmes,id',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        	'nom' => $data['nom'],
        	'prenom' => $data['prenom'],
        	'type' => $data['type'],
        	'programme_id' => $data['programme_id'],
        ]);
    }
}
