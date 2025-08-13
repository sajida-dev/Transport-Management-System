@props([
    'headers' => [],
    'rows' => [],
    'actions' => null, // Closure: function($row) {...}
    'striped' => true,
    'hover' => true,
    'compact' => false,
    'responsive' => true,
])

<div class="{{ $responsive ? 'overflow-x-auto' : '' }} w-full border border-gray-200">
    <table class="min-w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 text-xs font-semibold uppercase text-gray-500">
            <tr>
                @foreach ($headers as $header)
                    <th scope="col" class="px-6 py-4 whitespace-nowrap">
                        {{ $header }}
                    </th>
                @endforeach
                @if ($actions)
                    <th scope="col" class="px-6 py-4 text-right whitespace-nowrap">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody class="{{ $striped ? 'divide-y divide-gray-200' : '' }} bg-white">
            @forelse ($rows as $row)
                <tr class="{{ $hover ? 'hover:bg-gray-50 transition-colors' : '' }}">
                    @foreach ($row['columns'] as $cell)
                        <td class="{{ $compact ? 'px-4 py-2' : 'px-6 py-4' }} whitespace-nowrap align-middle text-gray-800">
                            {!! $cell !!}
                        </td>
                    @endforeach

                    @if ($actions)
                        <td class="{{ $compact ? 'px-4 py-2' : 'px-6 py-4' }} text-right whitespace-nowrap">
                            {!! $actions($row) !!}
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}"
                        class="px-6 py-4 text-center text-gray-500 text-sm">
                        No records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($slot->isNotEmpty())
    <div class="mt-4">
        {{ $slot }} {{-- Pagination or extra controls --}}
    </div>
@endif

