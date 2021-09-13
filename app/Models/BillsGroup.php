<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillsGroup extends Model
{
    use HasFactory, SoftDeletes;

    public function bills(){
        return $this->hasMany(Bill::class, 'group_id')->get();
    }

    public function users() {
        return $this->belongsToMany(User::class, 'bills_groups_assigned_users','user_id', 'group_id');
    }

    public function hasUser($user_id){
        return BillAssignedUser::where(['user_id'=>$user_id, 'group_id'=>$this->id])->get() != null;
    }
}
