@props([
    'message',
    'time',
    'outgoing' => false,
    'read' => false,
])

<div
    @class([
        'mb-3 flex',
        'justify-end' => $outgoing,
        'justify-start' => ! $outgoing,
    ])
>
    <div
        @class([
            'max-w-[80%] sm:max-w-[70%]',
            'rounded-2xl rounded-br-md bg-indigo-600 text-white' => $outgoing,
            'rounded-2xl rounded-bl-md bg-white text-gray-900 shadow-sm ring-1 ring-gray-200' => ! $outgoing,
        ])
    >
        <div class="px-4 py-2.5">

            <p class="whitespace-pre-wrap break-words text-sm leading-6">
                {{ $message }}
            </p>

            <div
                @class([
                    'mt-1 flex items-center justify-end gap-1 text-[10px]',
                    'text-indigo-200' => $outgoing,
                    'text-gray-400' => ! $outgoing,
                ])
            >
                <span>{{ $time }}</span>

                @if ($outgoing)
                    @if ($read)
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-3.5 w-3.5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m4.5 12.75 6 6 9-13.5"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m10.5 12.75 6-7.5"
                            />
                        </svg>
                    @else
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-3.5 w-3.5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m5 12 5 5L20 7"
                            />
                        </svg>
                    @endif
                @endif
            </div>

        </div>
    </div>
</div>