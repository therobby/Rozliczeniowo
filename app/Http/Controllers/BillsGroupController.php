<?php

namespace App\Http\Controllers;

use App\Models\BillGroupAssignedUser;
use App\Models\BillsGroup;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillsGroupController extends Controller
{
    // get user bills groups
    public function index() {
            $user_id = auth()->id();

            $group = BillsGroup::find(['owner_id'=>$user_id]);
            
            return response()->json($group);
    }

    // save new bills group
    public function store(Request $request){
            $user_id = auth()->id();

            $validation = Validator::make($request->all(), [
                'title' =>'required',
                'description' =>'required',
            ]);

            if($validation->fails()){
                return response('Missing required user data', 400);
            }

            $group = new BillsGroup;
            $group->title = $request->input('title');
            $group->owner_id = $user_id;
            $group->description = $request->input('description');
            
            $group->save();

            $group->addUser($user_id);
            $role_id = Role::where(['slug'=>'owner'])->first()->id;
            $group->userRole($user_id, $role_id);

            return response(201);
    }

    // get bills group with id
    public function show(BillsGroup $billgroup){

            if(!$this->checkIfUserInBillGroupGID(auth()->id(), $billgroup->id)){
                return response(401);
            }
            
            return response()->json($billgroup);
    }

    // update bills group data
    public function update(Request $request, BillsGroup $billgroup){
            if(!$this->checkIfUserInBillGroupGID(auth()->id(), $billgroup->id)){
                return response(401);
            }

            $validation = Validator::make($request->all(), [
                'title' =>'required',
                'description' =>'required',
            ]);

            if($validation->fails()){
                return response('Missing required data', 400);
            }
            if($request->has('title'))
                $billgroup->title = $request->input('title');
            if($request->has('description'))
                $billgroup->description = $request->input('description');
            
            $billgroup->save();

            return response(200);
    }

    // delete bills group
    public function destroy(BillsGroup $billgroup){
            $user_id = auth()->id();

        if(!$this->checkIfUserInBillGroupGID(auth()->id(), $billgroup->id)){
            return response(401);
        }

        if($billgroup->owner_id === auth()->id()){
            $billgroup->delete();

            return response(200);
        }
        return response('Only owner can do that.',401);
    }
    
    // show users in group
    public function getUsers(BillsGroup $billgroup){
        if(!$this->checkIfUserInBillGroupGID(auth()->id(), $billgroup->id)){
            return response(401);
        }

        return response()->json($billgroup->users());
    }

    // add user to group
    public function addUser(BillsGroup $billgroup, User $user){
        if(!$this->checkIfUserInBillGroupGID(auth()->id(), $billgroup->id)){
            return response(401);
        }

        $billgroup->addUser($user->id);
        return response(200);
    }

    // remove user from group
    public function removeUser(BillsGroup $billgroup, User $user){
        if(!$this->checkIfUserInBillGroupGID(auth()->id(), $billgroup->id)){
            return response(401);
        }

        $billgroup->removeUser($user->id);
        return response(200);
    }

    // update user role in group
    public function updateUserRole(BillsGroup $billgroup, User $user, Request $request){

        if(!$this->checkIfUserInBillGroupGID(auth()->id(), $billgroup->id)){
            return response(401);
        }

        if($request->has('role')){
            $role = Role::where('slug', $request->input('role'))->first();

            if($role){
                $billgroup->userRole($user->id, $role->id);
                return response(200);
            }
            return response(409);
        }
        return response(409);
    }
}
