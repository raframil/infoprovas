<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class RoleAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        try {
            //Access token from the request        
            $token = JWTAuth::parseToken();
            //Try authenticating user       
            $user = $token->authenticate();
        } catch (TokenExpiredException $e) {
            //Thrown if token has expired        
            return $this->unauthorized('Token expirado. Por favor, faça login novamente.');
        } catch (TokenInvalidException $e) {
            //Thrown if token invalid
            return $this->unauthorized('Token inválido. Por favor, faça login novamente.');
        } catch (JWTException $e) {
            //Thrown if token was not found in the request.
            return $this->unauthorized('Por favor, envie um Bearer Token com a requisição');
        }
        //If user was authenticated successfully and user is in one of the acceptable roles, send to next request.
        if ($user && in_array($user->role, $roles)) {
            return $next($request);
        }

        return $this->unauthorized();
    }

    private function unauthorized($message = null)
    {
        return response()->json([
            'message' => $message ? $message : 'Sem autorização para acessar o recurso',
            'success' => false
        ], 401);
    }
}
