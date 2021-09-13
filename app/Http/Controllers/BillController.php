<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillsGroup;
use App\Models\Product;
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

            $billsgroup = BillsGroup::find($group_id);
            if($billsgroup && !$billsgroup->hasUser(auth()->id())){
                return response(401);
            }

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
            if($billsgroup && !$billsgroup->hasUser(auth()->id())){
                return response(401);
            }
            
            if($billsgroup){
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
            
            $bill = Bill::find(['id' => $id])->first();

            if(!$bill->group()->hasUser($user_id)){
                return response(401);
            }

            if($request->has('title'))
                $bill->title = $request->input('title');
            if($request->has('description'))
                $bill->description = $request->input('description');
            

                \var_dump($bill);
            $bill->save();

            return response(200);
        }
        return response(401);
    }

    // delete bill
    public function destroy($id){
        if(auth()->check()){
            $user_id = auth()->id();

            $billsgroup = BillsGroup::find($id);
            if($billsgroup && !$billsgroup->hasUser(auth()->id())){
                return response(401);
            }

            $bill = Bill::find(['owner_id'=>$user_id, 'id' => $id])->first();
            $bill->delete();

            return response(200);
        }
        return response(401);
    }

    public function billProducts($id){
        if(auth()->check()){
            $user_id = auth()->id();

            $billsgroup = BillsGroup::find($id);
            if($billsgroup && !$billsgroup->hasUser(auth()->id())){
                return response(401);
            }

            $bill = Bill::find(['owner_id'=>$user_id, 'id' => $id])->first();

            return response()->json($bill->products());
        }
        return response(401);
    }

    public function createBillProduct(Request $request, $id){
        if(auth()->check()){

            $validation = Validator::make($request->all(), [
                'name' =>'required',
                'original_price' =>'required',
                'original_currency' =>'required'
            ]);

            if($validation->fails()){
                return response('Missing required user data', 400);
            }

            $product = new Product();
            $product->name = $request->input('name');
            $product->original_price = $request->input('original_price');
            $product->bill_id = $id;
            $product->original_currency = $request->input('original_currency');
            
            $product->save();

            return response(201);
        }
        return response(401);
    }
}
 