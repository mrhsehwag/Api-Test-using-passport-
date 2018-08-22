<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use Validator;
use Route;
use Hash;
use Mobily;
//  use GuzzleHttp\Client;
class AuthController extends Controller
{

    private $client;
    public function __construct(){
        $this->client=Client::find(2);
    }
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => bcrypt( $request->password),
            'random_key'=>rand(11111,888888)
           
        ]);
        
        // // send SMS to verifying 
        // Mobily::send($user->mobile,$user->random_key);

        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' =>  request('email'),
            'password' => request('password'),
            'scope' => '*'
        ];

        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');
        return Route::dispatch($proxy);
        // return response(['data'=>json_decode((string) $response->getBody(), true)]);
    }

    public function verifyUser(Request $request,$id){
        $user=UserApi::find($id);
        if($request->key_value=$user->random_key){
            $user->verified=true;
            $user->save(); 
            return response(['success'=>'the user verified succssfully']);
        }else{
            return response(['error'=>'the key is incorrect']);
        }
    }

    
    public function login(Request $request){
        $request->validate([
            'mobile'=>'required',
            'password'=>'required'
        ]);

        $user=UserApi::where('mobile',$request->mobile)->first();

        if(!$user){
            return response(['error'=>'user not found']);
        }

        if(Hash::check($request->password,$user->password)){

             //start requesting token
             $params = [
                'grant_type' => 'password',
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
                'username' => request('name').'@gmail.com',
                'password' => request('password'),
                'scope' => '*'
            ];
    
            $request->request->add($params);
            $proxy = Request::create('oauth/token', 'POST');
            return Route::dispatch($proxy);
            // return response(['data'=>json_decode((string) $response->getBody(), true)]);
        }
    }
    
}
