<?php

namespace App\Http\Middleware;

use Closure;

class ProfSeulement
{
    /**
     * filtre pour protéger les étudiants de prendre certaines routes
     * Note: pourrait probablement être remplacé par un système comme Sentry, mais ca fait la job pour l'instant. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if (\Auth::user()->type != 'p') {
    		return redirect('/home');
    	}
    	
    	return $next($request);
    }
}
