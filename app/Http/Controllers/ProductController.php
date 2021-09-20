<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // get product assigned to bill
    public function billProducts(Bill $bill)
    {
        if ($this->checkIfUserInBillGroupBID(auth()->id(), $bill->id)) {
            return response('',401);
        }

        return response()->json($bill->products());
    }

    // add product to bill
    public function createBillProduct(Bill $bill, Request $request)
    {
        if ($this->checkIfUserInBillGroupBID(auth()->id(), $bill->id)) {
            return response('',401);
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'original_price' => 'required',
            'original_currency' => 'required'
        ]);

        if ($validation->fails()) {
            return response('Missing required data', 400);
        }

        $product = new Product();
        $product->name = $request->input('name');
        $product->original_price = $request->input('original_price');
        $product->price = $product->original_price;
        $product->bill_id = $bill->id;
        $product->original_currency = $request->input('original_currency');

        $product->save();

        return response('',201);
    }

    // remove product from bill
    public function removeBillProduct(Bill $bill, Product $product)
    {
        if ($this->checkIfUserInBillGroupBID(auth()->id(), $bill->id)) {
            return response('',401);
        }

        $product->delete();
        return response(200);
    }

    // update product in bill
    public function updateBillProduct(Bill $bill, Product $product, Request $request)
    {

        if ($this->checkIfUserInBillGroupBID(auth()->id(), $bill->id)) {
            return response(401);
        }

        if ($request->has('name'))
            $product->name = $request->input('name');
        if ($request->has('original_price'))
            $product->original_price = $request->input('original_price');
        if ($request->has('original_currency'))
            $product->original_currency = $request->input('original_currency');

        $bill->save();

        return response(200);
    }
}
