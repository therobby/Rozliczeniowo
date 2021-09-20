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

        $billUsers = BillGroupAssignedUser::where(['group_id'=>$this->id])->get();
        
        foreach($billUsers as $user){
            $user->role = $user->role();
        }

        return $billUsers;
    }

    public function hasUser($user_id){
        return BillGroupAssignedUser::where(['user_id'=>$user_id, 'group_id'=>$this->id])->get() != null;
    }

    public function addUser($user_id){
        $newGroupUser = new BillGroupAssignedUser();
        $newGroupUser->user_id = $user_id;
        $newGroupUser->group_id = $this->id;
        $newGroupUser->role_id = Role::where(['slug'=>'read'])->first()->id;
        $newGroupUser->save();
    }

    public function removeUser($user_id){
        $groupUser = BillGroupAssignedUser::where(['user_id'=>$user_id, 'group_id'=>$this->id])->first();
        $groupUser->delete();
    }

    public function setUserRole($user_id, $role_id){
        $groupUser = BillGroupAssignedUser::where(['user_id'=>$user_id, 'group_id'=>$this->id])->first();
        $groupUser->role_id = $role_id;
        $groupUser->save();
    }

    public function getUserRole($user_id){
        $groupUser = BillGroupAssignedUser::where(['user_id'=>$user_id, 'group_id'=>$this->id])->first();
        return $groupUser->role();
    }

    
}
