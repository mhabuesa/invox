<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
     // Permissions Method
    public function __construct()
    {
        $this->setPermissions([
            'index'   => 'role_management',
        ]);
    }
    public function index()
    {
        $permissions = Permission::all();
        $roles = Role::all();
        $users = User::all();
        return view('role.index', compact('permissions', 'roles', 'users'));
    }
    public function permission_store(Request $request)
    {
        $request->validate([
            'permission_name' => 'required|string|max:255|unique:permissions,name',
        ]);
        Permission::create(['name' => $request->permission_name]);
        return redirect()->route('role.index')->with('success', 'Permission created successfully.');
    }

    public function create_role(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->role_name]);
        $permission = Permission::findOrFail($request->permission);
        $role->givePermissionTo($permission);

        return redirect()->route('role.index')->with('success', 'Permission assigned successfully.');
    }

    public function role_edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('role.edit_role', compact('permissions', 'role'));
    }

    public function role_update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'required|array',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->role;
        $role->save();

        // Sync Permissions
        $role->syncPermissions($request->permissions);

        return redirect()->route('role.index')->with('success', 'Role updated successfully.');
    }

    public function role_delete($id)
    {
        if(Auth::user()->email == 'demo@invox.com'){
            return redirect()->back()->with('error', 'Demo user can not perform this action.');
        }
        $role = Role::findOrFail($id);
        DB::table('role_has_permissions')->where('role_id', $id)->delete(); // Remove Permission from role
        $role->delete(); // Delete the role
        return redirect()->route('role.index')->with('success', 'Permission deleted successfully.');
    }

    public function role_assign(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->assignRole($request->role);
        return redirect()->route('role.index')->with('success', 'Role assigned successfully.');
    }

    public function user_role_delete($id)
    {
        if(Auth::user()->email == 'demo@invox.com'){
            return redirect()->back()->with('error', 'Demo user can not perform this action.');
        }
        $user = User::findOrFail($id);
        DB::table('model_has_roles')->where('model_id', $id)->delete(); // Remove role from user
        return redirect()->route('role.index')->with('success', 'User role deleted successfully.');
    }
}
