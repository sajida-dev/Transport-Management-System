@props([
    'headers' => [],
    'rows' => [],
    'striped' => true,
    'hover' => true,
    'compact' => false,
    'responsive' => true
])

<div class="{{ $responsive ? 'overflow-x-auto' : '' }} rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $header }}
                    </th>
                @endforeach
                @if(isset($actions))
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                @endif
            </tr>
        </thead>
        <tbody class="{{ $striped ? 'divide-y divide-gray-200 bg-white' : 'bg-white divide-y divide-gray-200' }}">
            @if(count($rows) > 0)
                @foreach($rows as $row)
                    <tr class="{{ $hover ? 'hover:bg-gray-50' : '' }} {{ $striped && $loop->even ? 'bg-gray-50' : '' }}">
                        @foreach($row as $cell)
                            <td class="{{ $compact ? 'px-6 py-2' : 'px-6 py-4' }} whitespace-nowrap text-sm text-gray-900">
                                {{ $cell }}
                            </td>
                        @endforeach
                        @if(isset($actions))
                            <td class="{{ $compact ? 'px-6 py-2' : 'px-6 py-4' }} whitespace-nowrap text-right text-sm font-medium">
                                {{ $actions($row) }}
                            </td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="{{ count($headers) + (isset($actions) ? 1 : 0) }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        No records found.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@if(isset($pagination))
    <div class="mt-4">
        {{ $pagination }}
    </div>
@endif