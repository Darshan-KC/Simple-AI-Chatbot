@props(['name', 'message', 'time', 'initials', 'unread' => 0, 'online' => false, 'active' => false])



<div>
    <!-- Smile, breathe, and go slowly. - Thich Nhat Hanh -->
    <button type="button"
        {{ $attributes->merge([
            'class' => 'group flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left transition',
        ]) }}
        @class([
            'bg-indigo-50' => $active,
            'hover:bg-gray-50' => !$active,
        ])>
        {{-- Avatar --}}
        <x-chat.avatar :initials="$initials" size="md" :online="$online" />

        {{-- Content --}}
        <div class="min-w-0 flex-1">

            {{-- Name + Time --}}
            <div class="flex items-center justify-between gap-2">

                <p @class([
                    'truncate text-sm',
                    'font-semibold text-indigo-900' => $active,
                    'font-semibold text-gray-900' => !$active && $unread,
                    'font-medium text-gray-700' => !$active && !$unread,
                ])>
                    {{ $name }}
                </p>

                <span @class([
                    'shrink-0 text-[11px]',
                    'text-indigo-600' => $active,
                    'font-medium text-gray-700' => !$active && $unread,
                    'text-gray-400' => !$active && !$unread,
                ])>
                    {{ $time }}
                </span>

            </div>

            {{-- Message + unread --}}
            <div class="mt-0.5 flex items-center justify-between gap-2">

                <p @class([
                    'truncate text-xs',
                    'text-indigo-700' => $active,
                    'font-medium text-gray-700' => !$active && $unread,
                    'text-gray-500' => !$active && !$unread,
                ])>
                    {{ $message }}
                </p>

                @if ($unread > 0)
                    <span
                        class="flex h-5 min-w-5 shrink-0 items-center justify-center rounded-full bg-indigo-600 px-1 text-[10px] font-semibold text-white">
                        {{ $unread > 99 ? '99+' : $unread }}
                    </span>
                @endif

            </div>

        </div>
    </button>
</div>
