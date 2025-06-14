<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles']);

        $role = Role::create(['name' => $request->name]);

        // Vérifiez que les permissions existent
        $validPermissions = Permission::whereIn('id', $request->permissions)->pluck('id')->toArray();
        $role->syncPermissions($validPermissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }


    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }
    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|unique:roles,name,' . $role->id]);

        $role->update(['name' => $request->name]);

        // Vérifiez que les permissions existent
        $validPermissions = Permission::whereIn('id', $request->permissions)->pluck('id')->toArray();
        $role->syncPermissions($validPermissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
