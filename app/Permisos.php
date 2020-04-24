<?php

namespace App;


use Spatie\Permission\Models\Permission;

class Permisos extends Permission
{
    // SQLServer datetime fix
    protected $dateFormat = 'Y-m-d H:i:s';
}
