<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request){
        $req = Validator::make($request->all(),[
            'username'=>'required|exists:users|alpha_dash',
            'password'=>'required|min:8'
        ]);

        $username = $request->input('username');

        $user = User::where(['username'=>$username])->first();

        if(!$req->fails()){
            if(Hash::check($request->input('password'), $user->password)){
                return $user->createToken($request->userAgent())->plainTextToken;
            } else {
                return response(['password'=>'Invalid password'],401);
            }
        }
        return response($req->errors(), 401);
    }

    public function register(Request $request){

        $req = Validator::make($request->all(),[
            'username'=>'required|unique:users|alpha_dash',
            'email'=>'required|unique:users|email',
            'password'=>'required|min:8'
        ]);

        if(!$req->fails()){
            // if(!User::where(['username'=>$request->username])->get()){
                $data = $req->validate();
                $user = new User();
                $user->username = $data['username'];
                $user->email = $data['email'];
                $user->password = Hash::make($data['password']);
                $user->save();
                return response(200);
            // }
            // return response(403);
        }
        return response($req->errors(), 409);
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

            $user = User::find($id)->first();
            
            if($user){
                $user->password = null;
                return response()->json($user);
            } else {
                return response()->json(null);
            }
        }
        return response(401);
    }

    // get user with username
    public function find($username){
        if(auth()->check()){

            $user = User::where('username', 'like' , '%'.$username.'%')->first();
            if($user){
                $user->password = null;
                return response()->json($user);
            } else {
                return response()->json(null);
            }
        }
        return response(401);
    }
    
    // update user data
    public function update(Request $request){

        
        if(auth()->check()){
            $user_id = auth()->id();
            
            $user = User::find(['id' => $user_id])->first();


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
    public function destroy(){
        if(auth()->check()){
            $user_id = auth()->id();

            $user = User::find(['id' => $user_id])->first();
            $user->delete();

            return response(200);
        }
        return response(401);
    }
}
