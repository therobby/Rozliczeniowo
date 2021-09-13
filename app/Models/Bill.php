<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function group(){
        return $this->belongsTo(BillsGroup::class, 'group_id', 'id')->first();
    }
}
 