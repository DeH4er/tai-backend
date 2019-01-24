<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Stats;

class UserController extends Controller
{
  public function authenticate(Request $request) {
    $credentials = $request->only('email', 'password');

    try {
      if (! $token = JWTAuth::attempt($credentials)) {
        return response()->json(['error' => 'invalid credentials'], 400);
      }
    } catch (JWTException $e) {
      return response()->json(['error' => 'could not create token'], 500);
    }

    return response()->json(compact('token'));
  }

  public function register(Request $request) {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6|confirmed'      
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->toJson(), 400);
    }

    $stats = new Stats;
    $stats->errors = 0;
    $stats->save();

    $user = User::create([
      'name' => $request->get('name'),
      'email' => $request->get('email'),
      'password' => Hash::make($request->get('password')),
      'level' => 0
    ]);

    $user->stats()->save($stats);
    $user->save();

    $token = JWTAuth::fromUser($user);

    return response()->json(compact('token'), 201);
  }

  public function getAuthenticatedUserResponse() {
    $user = $this->getAuthenticatedUser();
    return response()->json(compact('user'));
  }

  public static function getAuthenticatedUser() {
    try {
      if (! $user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['error' => 'user not found'],  404);
      }
    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
      return response()->json(['error' => 'token expired', $e->getStatusCode()]);
    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
      return response()->json(['error' => 'token invalid'], $e->getStatusCode());
    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
      return response()->json(['error' => 'token absent'], $e->getStatusCode());
    }

    return $user;
  }

}
