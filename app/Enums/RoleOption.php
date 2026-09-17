<?php

namespace App\Enums;

enum RoleOption: string
{
    case SuperAdmin = 'superadmin';
    case Admin = 'admin';
    case Technician = 'technician';
}
