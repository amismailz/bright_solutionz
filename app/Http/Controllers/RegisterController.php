<?php

namespace App\Http\Controllers;


use App\Enums\RoleTypeEnum;
use App\Http\Requests\Basic\UserRegisterationRequest;
use App\Http\Requests\DoctorRegisterationRequest;
use App\Models\AcademicQualification;
use App\Models\Association;
use App\Models\Certificate;
use App\Models\City;
use App\Models\Experience;
use App\Models\languageSkills;
use App\Models\Point;
use App\Models\Range;
use App\Models\Role;
use App\Models\Skills;
use App\Models\Speciality;
use App\Models\User;
use App\Services\DoctorRegistrationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{

    // protected DoctorRegistrationService $registrationService;

    // public function __construct(DoctorRegistrationService $registrationService)
    // {
    //     $this->registrationService = $registrationService;
    // }

    public function register()
    {
        //  $roles = Roles::select(['id','name'])->get();
        $points = Point::select(['id', 'name'])->get();
        $associations = Association::select(['id', 'name'])->get();
        $ranges = Range::select(['id', 'name'])->get();
        $allowedRoles = [
            'Distributor' => \App\Enums\RoleTypeEnum::Distributor->value,
//            'Supervisor' => \App\Enums\RoleTypeEnum::Supervisor->value,

        ];

        $roleLabels = [
            \App\Enums\RoleTypeEnum::Distributor->value => 'Distributor',
//            \App\Enums\RoleTypeEnum::Supervisor->value => 'Supervisor',

        ];

        $roleLabels = \App\Enums\RoleTypeEnum::labels();
        return view('register.register', get_defined_vars());
    }


    public function store(UserRegisterationRequest $request)
    {
        try {
            $role = $request['role'];
            if ($role === RoleTypeEnum::Distributor->name) {
                $status = 'active';
            } else {
                $status = 'inactive';
            }
            $user = User::create([
                'name' => $request['name'],
                'username' => $request['username'],
                'email' => $request['email'],
                'association_id' => $request['association_id'],
                'range_id' => $request['range_id'],
                'password' => Hash::make($request['password']),
                'phone' => $request['phone'] ?? null,
                'gender' => null,
                'disallow_location_track' => '1',
                'status' => $status
            ]);

            $user->points()->sync($request['point_ids'] ?? []);
            if ($role === RoleTypeEnum::Distributor->name) {
                $user->assignRole(RoleTypeEnum::Distributor->value);
            } else if ($role === RoleTypeEnum::Supervisor->name)
                $user->assignRole(RoleTypeEnum::Supervisor->value);


            return redirect()->back()->with('success', __('The account has been created successfully. Please contact the administration to activate the account.'));
            //  return redirect()->intended(url('dashboard'));
        } catch (\Throwable $e) {
            // dd($e);
            Log::error('Registration error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['general' => __('Registration failed. Please try again later.')]);
        }
    }
}
