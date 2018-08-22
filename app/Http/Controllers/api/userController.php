<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\userResource;
use App\User;
use Mobily;

class userController extends Controller
{
   public function index(){
       $users=userResource::collection(User::all());
       return response($users,200);
  
   }

   public function show($id){
    $user= User::find($id);
    if($user){
        $user2=new userResource($user);
        return response($user2,200);
    }else{
        return response(['error'=>'the user not found','status'=>false]);
    }

   }
    public function update(Request $request, $id)
    {
      
        $validatorData=$request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        // if($validatorData->fails()){
        //     return response(['error'=>$validatorData->errors(),'status'=>404]);
        // }

        $user=User::find($id);
        if(!$user){
            return response(['error'=>'this user not found','status'=>404]);
        
        } 
            $user->update($request->all());
            return response(['data'=>$user, 'success'=>'updated successfully','status'=>201]);
    
    }

    public function delete($id){
        $user=User::find($id)->delete();
        return response([ 'success'=> ' deleted successfully','status'=>201]);
    }
}
