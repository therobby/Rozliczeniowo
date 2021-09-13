<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillsGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillController extends Controller
{
    // get user bills from all groups
    public function index() {
        if(auth()->check()){
            $user_id = auth()->id();

            $bills = Bill::find(['owner_id'=>$user_id]);
            
            return response()->json($bills);
        }
        return response(401);
    }

    // save new bill
    public function store(Request $request, $group_id){
        if(auth()->check()){
            $user_id = auth()->id();

            $validation = Validator::make($request->all(), [
                'title' =>'required',
                'description' =>'required'
            ]);

            if($validation->fails()){
                return response('Missing required user data', 400);
            }

            $bill = new Bill;
            $bill->title = $request->input('title');
            $bill->owner_id = $user_id;
            $bill->group_id = $group_id;
            $bill->description = $request->input('description');
            
            $bill->save();

            return response(201);
        }
        return response(401);
    }

    // get bill with id
    public function show($id){
        if(auth()->check()){
            $user_id = auth()->id();

            $bill = Bill::find(['owner_id'=>$user_id, 'id' => $id])->first();
            
            return response()->json($bill);
        }
        return response(401);
    }

    // get all bills for group
    public function groupBills($id){
        if(auth()->check()){
            $billsgroup = BillsGroup::find($id);
            // dd($billsgroup->bills());
            if($billsgroup && $billsgroup->hasUser(auth()->id())){
                $bills = $billsgroup->bills();
            
                return response()->json($bills);
            } 
            return response()->json(null);
        }
        return response(401);
    }

    // update bill data
    public function update(Request $request, $id){

        
        if(auth()->check()){
            $user_id = auth()->id();

            $validation = Validator::make($request->all(), [
                'title' =>'required',
                'description' =>'required',
                'fixed_price' =>'required',
                'currency' =>'required',
            ]);

            if($validation->fails()){
                return response('Missing required user data', 400);
            }
            
            $bill = Bill::find(['owner_id'=>$user_id, 'id' => $id])->first();

            $bill->title = $request->input('title');
            $bill->description = $request->input('description');
            $bill->fixed_price = $request->input('fixed_price');
            $bill->currency = $request->input('currency');
            
            $bill->save();

            return response(200);
        }
        return response(401);
    }

    // delete bill
    public function destroy($id){
        if(auth()->check()){
            $user_id = auth()->id();

            $bill = Bill::find(['owner_id'=>$user_id, 'id' => $id])->first();
            $bill->delete();

            return response(200);
        }
        return response(401);
    }
}
 