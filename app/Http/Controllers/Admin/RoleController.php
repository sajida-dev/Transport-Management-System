<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:roles,name',
                'display_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,id',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            DB::commit();

            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role creation failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An error occurred while creating the role. Please try again.');
        }
    }

    public function show($id)
    {
        $role = Role::with('permissions', 'users')->findOrFail($id);
        return response()->json($role);
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:roles,name,' . $id,
                'display_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,id',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $role = Role::findOrFail($id);
            $role->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active')
            ]);

            // Handle empty permissions array (when all permissions are unchecked)
            $permissions = $request->input('permissions', []);
            $role->permissions()->detach(); // Detach all permissions first
            $role->permissions()->sync($permissions); // Sync the new permissions

            DB::commit();

            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role update failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An error occurred while updating the role. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $role = Role::findOrFail($id);
            $role->delete();
            DB::commit();

            return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role deletion failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An error occurred while deleting the role. Please try again.');
        }
    }

    public function getRoles()
    {
        $roles = Role::active()->get();
        return response()->json($roles);
    }

    public function toggleStatus($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->update(['is_active' => !$role->is_active]);

            return response()->json([
                'message' => 'Role status updated successfully',
                'role' => $role
            ]);
        } catch (\Exception $e) {
            Log::error('Role status toggle failed: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'An error occurred while updating the role status. Please try again.'], 500);
        }
    }
}
