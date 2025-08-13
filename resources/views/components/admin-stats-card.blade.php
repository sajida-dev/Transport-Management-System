@props(['icon', 'label', 'value', 'color'])

<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="{{ $icon }} text-2xl text-{{ $color }}-600"></i>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">{{ $label }}</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ $value }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
