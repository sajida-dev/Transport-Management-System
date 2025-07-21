@props([
    'title',
    'value',
    'icon' => null,
    'description' => null,
    'change' => null,
    'trend' => 'neutral' // can be 'up', 'down', or 'neutral'
])

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow rounded-lg']) }}>
    <div class="p-5">
        <div class="flex items-center">
            @if($icon)
                <div class="flex-shrink-0">
                    <i class="fas {{ $icon }} text-gray-400 text-2xl"></i>
                </div>
            @endif
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">{{ $title }}</dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-semibold text-gray-900">{{ $value }}</div>
                        
                        @if($change)
                            <div class="ml-2 flex items-baseline text-sm font-semibold 
                                {{ $trend === 'up' ? 'text-green-600' : ($trend === 'down' ? 'text-red-600' : 'text-gray-500') }}">
                                <i class="fas fa-{{ $trend === 'up' ? 'arrow-up' : ($trend === 'down' ? 'arrow-down' : 'minus') }} mr-0.5"></i>
                                {{ $change }}
                            </div>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    @if($description)
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm text-gray-500">
                {{ $description }}
            </div>
        </div>
    @endif
</div>