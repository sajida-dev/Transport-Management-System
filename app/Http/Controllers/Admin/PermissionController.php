<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:permissions,name',
                'display_name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $permission = Permission::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            DB::commit();

            return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Permission creation failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An error occurred while creating the permission. Please try again.');
        }
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:permissions,name,' . $id,
                'display_name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $permission = Permission::findOrFail($id);
            $permission->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            DB::commit();

            return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Permission update failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An error occurred while updating the permission. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $permission = Permission::findOrFail($id);
            $permission->delete();
            DB::commit();
            return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Permission deletion failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An error occurred while deleting the permission. Please try again.');
        }
    }
}
