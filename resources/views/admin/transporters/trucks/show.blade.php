@extends('admin.layouts.app')

@section('title', 'Truck Details')

@section('content')
<div class="min-h-screen bg-gray-100">
  <main class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Header -->
      <div class="mb-8 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Truck Details</h1>
          <p class="mt-1 text-sm text-gray-500">View all the details about this truck.</p>
        </div>
        <a href="{{ route('admin.transporters.trucks.index', $transporter) }}" 
        class="inline-flex items-center px-4 py-2 bg-white text-gray-700 rounded-md border hover:bg-gray-50">
          <i class="fas fa-arrow-left mr-2"></i>Back to Trucks
        </a>
      </div>

      <!-- Truck Information Section -->
      <div class="bg-white shadow sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg font-medium text-gray-900">Truck Information</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ([
              'ID' => $truck->id,
              'Registration Number' => $truck->registration_number,
              'Make' => $truck->make,
              'Model' => $truck->model,
              'Year' => $truck->year,
              'Color' => $truck->color,
              'Type' => ucfirst(str_replace('_', ' ', $truck->type)),
              'Capacity (tonnes)' => $truck->capacity_tonnes,
              'Dimensions (L×W×H m)' => "{$truck->length_meters} × {$truck->width_meters} × {$truck->height_meters}",
              'Status' => $truck->status,
              'Notes' => $truck->notes,
            ] as $label => $value)
            <div>
              <dt class="text-sm font-medium text-gray-500">{{ $label }}</dt>
              <dd class="mt-1 text-sm text-gray-900">{{ $value ?? 'N/A' }}</dd>
            </div>
            @endforeach
          </dl>
        </div>
      </div>

      <!-- Documents & Media Section -->
      <div class="bg-white shadow sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg font-medium text-gray-900">Documents & Images</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @if($truck->photo)
              <div>
                <p class="text-sm font-medium text-gray-700 mb-1">Truck Photo</p>
                <img src="{{ Storage::url($truck->photo) }}" class="w-full h-32 object-cover rounded" alt="Truck Photo">
              </div>
            @endif

            @if($truck->documents)
              @foreach (json_decode($truck->documents, true) as $doc)
                <div>
                  <p class="text-sm font-medium text-gray-700 mb-1">{{ basename($doc) }}</p>
                  <a href="{{ Storage::url($doc) }}" class="text-indigo-600 underline" target="_blank">View</a>
                </div>
              @endforeach
            @endif
          </div>
        </div>
      </div>

      <!-- Registration & Tracking Data Section -->
      <div class="bg-white shadow sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg font-medium text-gray-900">Tracking & Registration</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
            <div>
              <dt class="text-sm font-medium text-gray-500">Created At</dt>
              <dd class="mt-1 text-sm text-gray-900">{{ $truck->created_at?->format('Y-m-d H:i') ?? 'N/A' }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Updated At</dt>
              <dd class="mt-1 text-sm text-gray-900">{{ $truck->updated_at?->format('Y-m-d H:i') ?? 'N/A' }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Deleted At</dt>
              <dd class="mt-1 text-sm text-gray-900">{{ $truck->deleted_at?->format('Y-m-d H:i') ?? '—' }}</dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Optional: Tracking Data if you store coordinates -->
      @if($truck->tracking_data)
        <div class="bg-white shadow sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg font-medium text-gray-900">Tracking Data</h3>
          </div>
          <div class="border-t border-gray-200 px-4 py-5 sm:px-6 text-sm text-gray-900">
            <pre>{{ json_encode($truck->tracking_data, JSON_PRETTY_PRINT) }}</pre>
          </div>
        </div>
      @endif

    </div>
  </main>
</div>
@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show a temporary success message
        const button = event.currentTarget;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check text-green-500"></i>';
        setTimeout(() => {
            button.innerHTML = originalHTML;
        }, 1000);
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}
</script>
@endpush
@endsection
