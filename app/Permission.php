<?php

namespace App;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    public static function group($groupName)
    {
        return Permission::select('id', 'name', 'group_name', 'display_name', 'description')->where(['group_name' => $groupName])->get();
    }
}
