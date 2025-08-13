@props([
    'viewUrl' => null,
    'editUrl' => null,
    'deleteUrl' => null,
])

<div class="flex space-x-2">
    @if($viewUrl)
        <a href="{{ $viewUrl }}" class="text-blue-600 hover:text-blue-900" title="View">
            <i class="fas fa-eye"></i>
        </a>
    @endif

    @if($editUrl)
        <a href="{{ $editUrl }}" class="text-yellow-500 hover:text-yellow-700" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
    @endif

    @if($deleteUrl)
        <form action="{{ $deleteUrl }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @endif
</div>
