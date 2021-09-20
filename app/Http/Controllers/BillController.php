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
    public function index()
    {
        $user_id = auth()->id();

        $bills = Bill::find(['owner_id' => $user_id]);

        return response()->json($bills);
    }

    // save new bill
    public function store(Request $request, BillsGroup $billgroup)
    {
        $user_id = auth()->id();

        if ($this->checkIfUserInBillGroupGID(auth()->id(), $billgroup->id)) {
            return response(401);
        }
        // $billsgroup = BillsGroup::find($group_id);
        // if($billsgroup && !$billsgroup->hasUser(auth()->id())){
        //     return response(401);
        // }

        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validation->fails()) {
            return response('Missing required user data', 400);
        }

        $bill = new Bill;
        $bill->title = $request->input('title');
        $bill->owner_id = $user_id;
        $bill->group_id = $billgroup->id;
        $bill->description = $request->input('description');

        $bill->save();

        return response('',201);
    }

    // get bill with id
    public function show(Bill $bill)
    {
        if (!$this->checkIfUserInBillGroupBID(auth()->id(), $bill->id)) {
            return response('',401);
        }

        $bill->sum = 0;
        $bill->products = $bill->products();

        foreach($bill->products as $product){
            $bill->sum += $product->price;
        }

        return response()->json($bill);
    }

    // get all bills for group
    public function groupBills(BillsGroup $billgroup)
    {
        if (!$this->checkIfUserInBillGroupGID(auth()->id(), $billgroup->id)) {
            return response('',401);
        }

        // $billsgroup = BillsGroup::find($id);
        // if($billsgroup && !$billsgroup->hasUser(auth()->id())){
        //     return response(401);
        // }

        if ($billgroup) {
            $bills = $billgroup->bills();

            return response()->json($bills);
        }
        return response()->json(null);
    }

    // update bill data
    public function update(Bill $bill, Request $request)
    {
        $user_id = auth()->id();

        if (!$this->checkIfUserInBillGroupBID($user_id, $bill->id)) {
            return response('',401);
        }

        // $bill = Bill::find(['id' => $id])->first();
        // if(!$bill->group()->hasUser($user_id)){
        //     return response(401);
        // }

        if ($request->has('title'))
            $bill->title = $request->input('title');
        if ($request->has('description'))
            $bill->description = $request->input('description');

        $bill->save();

        return response(200);
    }

    // delete bill
    public function destroy(Bill $bill)
    {
        if (!$this->checkIfUserInBillGroupBID(auth()->id(), $bill->id)) {
            return response('',401);
        }

        // $billsgroup = BillsGroup::find($id);
        // if($billsgroup && !$billsgroup->hasUser(auth()->id())){
        //     return response(401);
        // }

        // $bill = Bill::find(['owner_id'=>$user_id, 'id' => $id])->first();
        $bill->delete();

        return response(200);
    }
}
