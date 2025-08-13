@extends('admin.layouts.app')
@section('title', 'Edit Role')

@section('content')
<div class="w-full px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Role</h1>

        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="bg-white shadow rounded-lg p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">Display Name <span class="text-red-500">*</span></label>
                    <input type="text" name="display_name" id="display_name" value="{{ old('display_name', $role->display_name) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('display_name') border-red-500 @enderror" required>
                    @error('display_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="is_active" id="is_active"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="1" {{ old('is_active', $role->is_active) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $role->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $role->description) }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Assign Permissions</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($permissions as $permission)
                        <label for="perm_{{ $permission->id }}" class="cursor-pointer">
                            <input type="checkbox" name="permissions[]" id="perm_{{ $permission->id }}" value="{{ $permission->id }}"
                                class="hidden peer"
                                {{ (is_array(old('permissions', $role->permissions->pluck('id')->toArray())) && in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray()))) ? 'checked' : '' }}>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border
                                text-gray-700 bg-gray-100 border-gray-300
                                peer-checked:bg-indigo-100 peer-checked:text-indigo-700 peer-checked:border-indigo-500
                                hover:bg-indigo-50">
                                {{ $permission->display_name }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('permissions') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.roles.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
