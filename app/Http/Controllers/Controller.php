<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Assign permission middleware for specified methods.
     *
     * @param  array  $permissions ['methodName' => 'permission_name', ...]
     * @return void
     */
    protected function setPermissions(array $permissions)
    {
        foreach ($permissions as $methods => $permissionName) {
            if (is_array($methods)) {
                $this->middleware("permission:{$permissionName}")->only($methods);
            } else {
                $this->middleware("permission:{$permissionName}")->only([$methods]);
            }
        }
    }
}
