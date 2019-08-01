<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use Validator;
use Response;

use GuzzleHttp\Client;

class APIRegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required',
            'password' => 'required|min:6',
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        if (config('services.recaptcha.enabled') && !$this->checkRecaptcha($request->get('token'), $request->ip())) {
            return response()->json('Recaptcha inválido.', 500);
        }
        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        $user = User::first();
        $token = JWTAuth::fromUser($user);

        return Response::json(compact('token'));
    }

    protected function checkRecaptcha($token, $ip)
    {
        $response = (new Client)->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => config('services.recaptcha.secret'),
                'response' => $token,
                'remoteip' => $ip,
            ],
        ]);

        $response = json_decode((string) $response->getBody(), true);

        return $response['success'];
    }
}
