<?php

namespace Database\Seeders;

use App\Enums\RoleTypeEnum;
use App\Models\Association;
use App\Models\Point;
use App\Models\Range;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSupervisorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::create([
            'name'            => 'Admin Supervisor',
            //'username'           => 'admin_supervisor',
            'email'           => 'admin2@supervisor.com',
            'password'        => Hash::make('12345678'),
            'phone'           => '5123456789',
            'gender'          => 'male',
           // 'status'          => 'active',
        ]);


        $user->assignRole(RoleTypeEnum::AdminSupervisor->value);


    }
}
