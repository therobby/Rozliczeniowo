<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBillRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bill_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        // This is important data

        $data = [
            [
                'name'=>'Read',
                'slug'=>'read'
            ],
            [
                'name'=>'Edit',
                'slug'=>'edit'
            ],
            [
                'name'=>'Owner',
                'slug'=>'owner'
            ]
        ];

        foreach($data as $d){
            $role = new Role();
            $role->name = $d['name'];
            $role->slug = $d['slug'];
            $role->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bill_roles');
    }
}
