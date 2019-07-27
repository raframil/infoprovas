<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use app\User;

class APILoginController extends Controller
{
  public function login()
  {
    // get email and password from request
    $credentials = request(['email', 'password']);

    // try to auth and get the token using api authentication
    if (!$token = auth('api')->attempt($credentials)) {
      // if the credentials are wrong we send an unauthorized error in json format
      return response()->json(['error' => 'Email e/ou senha inválidos. Por favor, tente novamente'], 401);
    }
    return response()->json([
      'token' => $token,
      'type' => 'bearer', // you can ommit this
      'expires' => auth('api')->factory()->getTTL() * 60, // time to expiration
    ]);
  }

  public function user(Request $request)
  {
    $user = User::find(Auth::user()->id);
    return response([
      'status' => 'success',
      'data' => $user
    ]);
  }

  public function refresh()
  {
    return response([
      'status' => 'success'
    ]);
  }

  public function logout()
  {
    Auth::guard('api')->logout();

    return response()->json([
      'status' => 'success',
      'message' => 'logout'
    ], 200);
  }
}
