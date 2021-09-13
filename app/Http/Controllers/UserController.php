<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function login(Request $request){
        $username = $request->input('username');

        $user = User::where(['username'=>$username])->first();

        if($user){
            if($request->has(['password'])){
                if(Hash::check($request->input('password'), $user->password)){
                    return $user->createToken($request->userAgent())->plainTextToken;
                } else {
                    return response("Invalid password",400);
                }
            } else {
                return response("No password provided",400);
            }
        }
        return response("User doesn't exists", 400);

    }

    public function register(Request $request){
        if($request->has(['username', 'email', 'password'])){
            $user = new User();
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return response(200);
        }
        return response("Missing required user data", 400);
    }

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


            if($request->has('username')){
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
