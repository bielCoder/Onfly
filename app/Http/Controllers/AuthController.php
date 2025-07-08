<?php

namespace App\Http\Controllers;

use App\Classes\Utilities\Response;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private $response;

    public function __construct(Response $response)
    {
        $this -> response = $response;
    }

    public function register(AuthRequest $request)
    {
       try{
             $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'access' => $request -> access
        ]);

        $token = JWTAuth::fromUser($user);

        return $this -> response -> format("auth",$request->header('Content-Type'),strtoupper($request->method()),compact('user', 'token'),null,"Usuário registrado com sucesso",202);
       } catch(\Exception $e)
       {
            return $this -> response -> error("auth",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
       } catch(\PDOException $e) {
            return $this -> response -> error("auth",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
       }

    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return $this -> response -> error("auth",$request->header('Content-Type'),strtoupper($request->method()),"Crendenciais Inválidas",401);
        }

        return $this -> response -> format("auth",$request->header('Content-Type'),strtoupper($request->method()),compact('token'),null,"Seja muito bem-vindo ".Auth::user() -> name,200);
    }

    public function me(Request $request)
    {
         return $this -> response -> format("auth",$request->header('Content-Type'),strtoupper($request->method()),JWTAuth::parseToken()->authenticate(),null,null,200);
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this -> response -> format("auth",$request->header('Content-Type'),strtoupper($request->method()),null,null,"Usuário deslogado com sucesso.",200);

        } catch(\Exception $e)
        {
            return $this -> response -> error("auth",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        } catch(\PDOException $e)
        {
            return $this -> response -> error("auth",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        }


    }
}
