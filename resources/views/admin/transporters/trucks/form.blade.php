@php
    $truck = $truck ?? null;
    $isEdit = isset($truck) && $truck->exists;
@endphp

<form 
    action="{{ $isEdit 
        ? route('admin.transporters.trucks.update', [$transporter, $truck]) 
        : route('admin.transporters.trucks.store', $transporter) 
    }}"
    method="POST"
    enctype="multipart/form-data"
    class="bg-white p-6 rounded-lg shadow"
>
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <x-select 
            name="transporter_id" 
            label="Transporter" 
            :options="[$transporter->id => $transporter->company_name]" 
            :value="old('transporter_id', $truck->transporter_id ?? $transporter->id)"
            required
        />

        <x-select 
            name="driver_id" 
            label="Driver (optional)" 
            :options="$drivers" 
            :value="old('driver_id', $truck->driver_id ?? '')"
        />

        <x-input name="registration_number" label="Registration Number" :value="old('registration_number', $truck->registration_number ?? '')" required />
        <x-input name="make" label="Make" :value="old('make', $truck->make ?? '')" required />
        <x-input name="model" label="Model" :value="old('model', $truck->model ?? '')" required />
        <x-input name="year" label="Year" type="number" :value="old('year', $truck->year ?? '')" required />
        <x-input name="color" label="Color" :value="old('color', $truck->color ?? '')" required />

        <x-select 
            name="type" 
            label="Truck Type" 
            :options="[
                'flatbed' => 'Flatbed',
                'box_truck' => 'Box Truck',
                'refrigerated' => 'Refrigerated',
                'tanker' => 'Tanker',
                'dump_truck' => 'Dump Truck',
                'lowboy' => 'Lowboy',
                'other' => 'Other'
            ]"
            :value="old('type', $truck->type ?? '')"
            required
        />

        <x-input name="capacity_tonnes" label="Capacity (Tonnes)" type="number" step="0.01" :value="old('capacity_tonnes', $truck->capacity_tonnes ?? '')" required />
        <x-input name="length_meters" label="Length (m)" type="number" step="0.01" :value="old('length_meters', $truck->length_meters ?? '')" required />
        <x-input name="width_meters" label="Width (m)" type="number" step="0.01" :value="old('width_meters', $truck->width_meters ?? '')" required />
        <x-input name="height_meters" label="Height (m)" type="number" step="0.01" :value="old('height_meters', $truck->height_meters ?? '')" required />

        <x-input name="engine_number" label="Engine Number" :value="old('engine_number', $truck->engine_number ?? '')" />
        <x-input name="chassis_number" label="Chassis Number" :value="old('chassis_number', $truck->chassis_number ?? '')" />
        <x-input name="insurance_policy_number" label="Insurance Policy #" :value="old('insurance_policy_number', $truck->insurance_policy_number ?? '')" />
        <x-input name="insurance_expiry" label="Insurance Expiry" type="date" :value="old('insurance_expiry', optional($truck?->insurance_expiry)->format('Y-m-d'))" />

        <x-input name="fitness_certificate_number" label="Fitness Certificate #" :value="old('fitness_certificate_number', $truck->fitness_certificate_number ?? '')" />
        <x-input name="fitness_expiry" label="Fitness Expiry" type="date" :value="old('fitness_expiry', optional($truck?->fitness_expiry)->format('Y-m-d'))" />

        <x-input name="permit_number" label="Permit Number" :value="old('permit_number', $truck->permit_number ?? '')" />
        <x-input name="permit_expiry" label="Permit Expiry" type="date" :value="old('permit_expiry', optional($truck?->permit_expiry)->format('Y-m-d'))" />

        <x-input name="photo" label="Truck Photo" type="file" />

        @if($isEdit && $truck->photo)
            <div class="md:col-span-3">
                <img src="{{ Storage::url($truck->photo) }}" alt="Truck Photo" class="h-24 rounded shadow" />
            </div>
        @endif
    </div>

    <x-textarea name="notes" label="Additional Notes" :value="old('notes', $truck->notes ?? '')" placeholder="Any additional information about the truck" rows="6"></x-textarea>
  

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Truck Documents (PDF, JPG, PNG)</label>
        <input type="file" name="documents[]" multiple class="w-full border rounded-md p-2">
        @if($isEdit && $truck->documents)
            <ul class="mt-2 text-sm text-gray-600">
                @foreach(json_decode($truck->documents, true) as $doc)
                    <li>
                        <a href="{{ Storage::url($doc) }}" target="_blank" class="text-indigo-600 underline">{{ basename($doc) }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="flex justify-end mt-4 gap-3">
        {{-- cancel button --}}
                <a href="{{ route('admin.transporters.trucks.index', $transporter) }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            {{ $isEdit ? 'Update Truck' : 'Save Truck' }}
        </button>
    </div>
</form>
