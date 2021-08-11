<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // get logged in user data
    public function index() {
        if(auth()->check()){
            $user = auth()->user();
            
            return response()->json($user);
        }
        return response(401);
    }

    // get user with id
    public function show($id){
        if(auth()->check()){
            $user_id = auth()->id();

            $user = User::find(['id' => $id])->first();
            
            $user->password = null;

            return response()->json($user);
        }
        return response(401);
    }
    
    // update user data
    public function update(Request $request, $id){

        
        if(auth()->check()){
            $user_id = auth()->id();
            
            $user = User::find(['id' => $id])->first();


            if($request->has('name')){
                $user->name = $request->input('name');
            }
            if($request->has('email')){
                $user->email = $request->input('email');
            }
            if($request->has('telephone')){
                $user->telephone = $request->input('telephone');
            }
            if($request->has('password')){
                $user->password = $request->input('password');
            }
            if($request->has('account_number')){
                $user->account_number = $request->input('account_number');
            }
            
            $user->save();

            return response(200);
        }
        return response(401);
    }

    // delete user
    public function destroy($id){
        if(auth()->check()){
            $user_id = auth()->id();

            $user = User::find(['id' => $id])->first();
            $user->delete();

            return response(200);
        }
        return response(401);
    }
}
