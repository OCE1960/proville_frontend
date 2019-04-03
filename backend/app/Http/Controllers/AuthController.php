<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
//use Illuminate\Foundation\Auth\User;
//use Illuminate\Http\Request;
use App\Http\Requests\SignUpRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use DB;
use Mail;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','signup','activate']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'		                =>	'required|max:255',
            'password'                  =>  'required|min:6',
        ]);

        $email = $request['email'];
        //$password = Hash::make($request['password']);

        $login = DB::table('users')
                ->select('email','password')
                ->where('email', $email)
                ->where('account_status',1)
                -> get();
        if(count($login) == 1 ){

            $credentials = request(['email', 'password']);
            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized To Login'], 401);
            }
            return $this->respondWithToken($token);
           

        }else{
            return response()->json(['error' => 'Account Not Activated '], 401);

        }


     /*   $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized To Login'], 401);
        }

        return $this->respondWithToken($token);
        */
    }

    public function signup(Request $request){
        $this->validate($request, [
            'email'		                =>	'required|unique:users|max:255',
            'password'                  =>  'required|min:6|confirmed',
            'password_confirmation'		=>  'required|same:password',
            'terms' => 'required',
        ]);

        $terms = $request['terms'];
        $email = $request['email'];
        $password = Hash::make($request['password']);
        $activation_code = substr(md5(time()),0,10).time();
        $account_status = 0;

        $signup = DB::table('users')->insert([
            'terms'		=> $terms,
            'email'			=> $email,
            'password' 	=> $password,
            'activation_code' => $activation_code,
            'account_status' => $account_status,
        ]);

        if( $signup ) {

            $data = array(
                'email' => $email,
                'bodymessage' => $activation_code,
            );

            $email = Mail::send('emails.contact',$data, function($message) use ($data){
                $message -> from('oce@oce.com.ng');
                $message -> replyTo('oce@oce.com.ng');
                $message -> to($data['email']);
                $message -> subject('Account Activation Code');

            });

            if($email){
                return Response() -> json([
                    'Success' => 'Activated Code Successfully sent to your email',
                ]);
            }
           // return $this -> signin($request);
        } 

    /* $user = User::create($request -> all());
     return $this -> login($request);*/

    }


  // ACCOUNT ACTIVATION 
    public function activate(Request $request, $id){
        $this->validate($request, [
            'activation_code' => 'required',
        ]);

        $activate = DB::table('users')  
                    -> where('activation_code', $id)
                    -> update(['account_status' => 1 ] );
        if(isset($activate)){
            return Response() -> json([
                'Success' => 'Account Successfully Updated',
            ]);

        }

        
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth() -> user(), 
        ]);
    }
}
