<div class="flex flex-col px-4 sm:px-6">
    <div class="mt-5">
        <form method="POST" action="{{ route('user-profile-information.update') }}" enctype="multipart/form-data"
            class="bg-white p-4 sm:p-6 shadow sm:rounded-md">
            @csrf
            @method('PUT')

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Left Side: Form Fields -->
                <div class="w-full lg:w-2/3">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Name -->
                        <div class="col-span-1">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required
                                value="{{ old('name', auth()->user()->name) }}" placeholder="e.g. John Doe"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>

                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="first_name" id="first_name" required
                                value="{{ old('first_name', auth()->user()->first_name) }}" placeholder="e.g. John"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="last_name" id="last_name" required
                                value="{{ old('last_name', auth()->user()->last_name) }}" placeholder="e.g. Doe"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" required
                                value="{{ old('email', auth()->user()->email) }}" placeholder="e.g. john@example.com"
                                autocomplete="username"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone
                                Number</label>
                            <input type="text" name="phone_number" id="phone_number"
                                value="{{ old('phone_number', auth()->user()->phone_number) }}"
                                placeholder="e.g. +1234567890"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select name="gender" id="gender"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Gender</option>
                                <option value="male"
                                    {{ old('gender', auth()->user()->gender) === 'male' ? 'selected' : '' }}>Male
                                </option>
                                <option value="female"
                                    {{ old('gender', auth()->user()->gender) === 'female' ? 'selected' : '' }}>Female
                                </option>
                                <option value="other"
                                    {{ old('gender', auth()->user()->gender) === 'other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                        </div>

                        <!-- NRC -->
                        <div>
                            <label for="nrc" class="block text-sm font-medium text-gray-700">NRC</label>
                            <input type="text" name="nrc" id="nrc"
                                value="{{ old('nrc', auth()->user()->nrc) }}" placeholder="e.g. 12/XYZ(N)123456"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>

                        <!-- Address -->
                        <div class="col-span-1 md:col-span-2 lg:col-span-3">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', auth()->user()->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Profile Photo Upload -->
                <div x-data="{ photoName: null, photoPreview: null }"
                    class="w-full lg:w-1/3 flex flex-col items-center mt-6 lg:mt-0 bg-white p-4 rounded-md shadow">
                    <input type="file" name="photo" id="photo" class="hidden" x-ref="photo"
                        x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                           " />

                    <label for="photo" class="mb-4 text-lg font-semibold text-gray-700">Profile Photo</label>

                    <!-- Current Photo -->
                    <div class="mt-2" x-show="!photoPreview">
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}"
                            class="rounded-full w-40 h-40 object-cover border border-gray-300 shadow-sm" />
                    </div>

                    <!-- Preview Photo -->
                    <div class="mt-2" x-show="photoPreview" style="display: none;">
                        <span
                            class="block rounded-full w-40 h-40 bg-cover bg-no-repeat bg-center border border-gray-300 shadow-sm"
                            x-bind:style="'background-image: url(\'' + photoPreview + '\')'">
                        </span>
                    </div>

                    <button type="button"
                        class="mt-6 w-full px-4 py-2 bg-gray-200 text-sm rounded-md hover:bg-gray-300"
                        x-on:click.prevent="$refs.photo.click()">
                        Select A New Photo
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
