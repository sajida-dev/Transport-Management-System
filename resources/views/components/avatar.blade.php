@props(['src', 'alt' => 'Photo'])

@if($src)
    <img src="{{ Storage::url($src) }}" alt="{{ $alt }}" class="w-10 h-10 rounded-full object-cover">
@else
    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-300 text-white">
        <i class="fas fa-user"></i>
    </div>
@endif
