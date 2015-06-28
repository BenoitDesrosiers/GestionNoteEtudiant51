<?php

namespace App\Models;

//nouveau avec 5.1
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

//avant la migration à 5.1
//use Zizaco\Confide\ConfideUser;
//use Zizaco\Confide\ConfideUserInterface;
 
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {
// avant 5.1 ConfideUserInterface {
//    use ConfideUser;
    
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'nom', 'prenom', 'type'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    /*
     * database relationships
    */
    
    // Un étudiant est associée à plusieurs Classe 
    // TODO: ce n'est pas tous les users qui sont étudiants. Faut vraiment avoir une classe étudiant qui est lié a un user de type e
    
    public function classes() {
    	return $this->belongsToMany('App\Models\Classe', 'etudiants_classes', 'etudiant_id', 'classe_id');
    }
}