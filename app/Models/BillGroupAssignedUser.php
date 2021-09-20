<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillGroupAssignedUser extends Model
{
    use HasFactory;

    protected $table = 'bills_groups_assigned_users';

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id')->first();
    }

    public function role(){
        return $this->hasOne(Role::class, 'id', 'role_id')->first();
    }
}
 