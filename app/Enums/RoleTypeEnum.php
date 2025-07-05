<?php

namespace App\Enums;

use Spatie\Permission\Models\Role;

enum RoleTypeEnum: string
{

    case SuperAdmin = 'super_admin';

    case Distributor = 'distributor';
    case Supervisor = 'supervisor';
    case AdminSupervisor = 'admin_supervisor';




    public static function labels(): array
    {
        return [
            self::SuperAdmin->value => __('Super Admin'),
            self::Distributor->value => __('Distributor'),
            self::Supervisor->value => __('Supervisor'),
            self::AdminSupervisor->value => __('Admin Supervisor'),
        ];
    }

    public static function getRole(self $role): ?Role
    {
        return Role::where('name', $role->value)->first();
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
