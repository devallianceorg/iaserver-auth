<?php

namespace App;

use Spatie\Permission\Models\Role;

class Roles extends Role
{
    // SQLServer datetime fix
    protected $dateFormat = 'Y-m-d H:i:s';
}
