<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Carbon\Carbon;
use App\Services\RegisterService;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json(['message' => 'Unauthorized'], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]);
       
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);     
    }

    public function register ()
    {
        return view('auth.register');
    }

    public function postRegister(Request $request)
    {
        
        try 
        {
            $new =  new RegisterService($request->only(['name','email','password']));
            $new->store();

            return response()->json([
                'message' => 'Successfully created user!'
            ], 201);
        } 

        catch(\Exception $e) 
        {
            dd($e->getMessage());
        }
        
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
