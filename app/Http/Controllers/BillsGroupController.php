<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillsGroupController extends Controller
{
    // get user bills groups
    public function index() {
        if(auth()->check()){
            $user_id = auth()->id();

            $group = BillsGroup::find(['owner_id'=>$user_id]);
            
            return response()->json($group);
        }
        return response(401);
    }

    // save new bills group
    public function store(Request $request){
        if(auth()->check()){
            $user_id = auth()->id();

            $request->validate([
                'title' =>'required',
                'description' =>'required',
            ]);

            $group = new BillsGroup;
            $group->title = $request->input('title');
            $group->owner_id = $user_id;
            $group->description = $request->input('description');
            
            $group->save();

            return response(201);
        }
        return response(401);
    }

    // get bills group with id
    public function show($id){
        if(auth()->check()){
            $user_id = auth()->id();

            $group = BillsGroup::find(['owner_id'=>$user_id, 'id' => $id])->first();
            
            return response()->json($group);
        }
        return response(401);
    }

    // update bills group data
    public function update(Request $request, $id){

        
        if(auth()->check()){
            $user_id = auth()->id();

            $request->validate([
                'title' =>'required',
                'description' =>'required',
                'users' =>'required',
            ]);
            
            $group = BillsGroup::find(['owner_id'=>$user_id, 'id' => $id])->first();

            $group->title = $request->input('title');
            $group->description = $request->input('description');
            
            $group->save();

            // TODO: handle changed users

            return response(200);
        }
        return response(401);
    }

    // delete bills group
    public function destroy($id){
        if(auth()->check()){
            $user_id = auth()->id();

            $group = BillsGroup::find(['owner_id'=>$user_id, 'id' => $id])->first();
            $group->delete();

            return response(200);
        }
        return response(401);
    }
    
}
