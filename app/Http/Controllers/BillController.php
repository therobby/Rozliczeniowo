<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillController extends Controller
{
    // get user bills
    public function index() {
        if(auth()->check()){
            $user_id = auth()->id();

            $bills = Bill::find(['owner_id'=>$user_id]);
            
            return response()->json($bills);
        }
        return response(401);
    }

    // 730 kalafior 

    // save new bill
    public function store(Request $request){
        if(auth()->check()){
            $user_id = auth()->id();

            $request->validate([
                'title' =>'required',
                'description' =>'required',
                'fixed_price' =>'required',
                'currency' =>'required',
            ]);

            $bill = new Bill;
            $bill->title = $request->input('title');
            $bill->description = $request->input('description');
            $bill->fixed_price = $request->input('fixed_price');
            $bill->currency = $request->input('currency');
            
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

    // update bill data
    public function update(Request $request, $id){

        
        if(auth()->check()){
            $user_id = auth()->id();

            $request->validate([
                'title' =>'required',
                'description' =>'required',
                'fixed_price' =>'required',
                'currency' =>'required',
            ]);
            
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
