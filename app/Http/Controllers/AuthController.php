<?php

namespace App\Http\Controllers;

use App\Classes\Utilities\Response;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,Token};
use App\Classes\Utilities\AuthCode;
use App\Mail\NotifyRecoveryGmail;
use App\Mail\NotifyTokenGmail;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    private $response;
    private $authCode;
    private $token;
    private $users;

    public function __construct(Response $response, AuthCode $authCode, Token $token, User $users)
    {
        $this -> response = $response;
        $this -> authCode = $authCode;
        $this -> token = $token;
        $this -> users = $users;
    }

    public function register(AuthRequest $request)
    {


       try{
        $data = $this -> token -> create([
            "token" => $this -> authCode -> random()
        ]);


        Mail::to($request->email)->send(new NotifyTokenGmail($request -> name,  $data -> token));

        return $this -> response ->format("token",$request->header('Content-Type'),strtoupper($request->method()),true,$data -> token,"Token registrado com sucesso.",202);

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

    public function checkToken(Request $request)
    {
       $finded =  $this -> token -> where('token',$request -> token) -> first();
       if($finded -> token === $request -> token)
       {
             $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'access' => $request -> access
        ]);
        $this -> token -> where('token',$request -> token) -> update([
            "confirmed" => true
        ]);
        $token = JWTAuth::fromUser($user);
        return $this -> response -> format("auth",$request->header('Content-Type'),strtoupper($request->method()),compact('user', 'token'),null,"Usuário registrado com sucesso",202);
       }

       return $this -> response -> error("auth",$request->header('Content-Type'),strtoupper($request->method()),"Token não encontrado",404);
    }


    public function recovery(Request $request)
    {
        try {
            $user = $this -> users -> where('email',$request -> email)->first();
            if($user)
            {
                 $link = config('app.url').':8080/recovery';
                 Mail::to($request->email)->send(new NotifyRecoveryGmail($user -> email,$user -> name,$link));
                 return $this -> response -> format("auth",$request->header('Content-Type'),strtoupper($request->method()),null,null,"Link para redefinição de senha enviado.",200);
            }
            return $this -> response -> error("auth","application\json","post","Usuário não encontrado.",404);
        }catch(\Exception $e)
        {
            return $this -> response -> error("auth",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        } catch(\PDOException $e)
        {
            return $this -> response -> error("auth",$request->header('Content-Type'),strtoupper($request->method()),$e -> getMessage(),500);
        }
    }


}
