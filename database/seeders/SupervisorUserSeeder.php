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

class SupervisorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {



        $user = User::create([
            'name'            => 'Supervisor User',
           // 'username'           => 'younies ',
            'email'           => 'younies@supervisor.com',
            'password'        => Hash::make('12345678'),
            'phone'           => '5123456789',
            'gender'          => 'male',
            'association_id'  => 6,
           // 'status'          => 'active',
        ]);


        $user->assignRole(RoleTypeEnum::Supervisor->value);


    }
}
