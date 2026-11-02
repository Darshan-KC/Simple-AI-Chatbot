@use('Illuminate\Support\Str')

    <div class="flex h-[calc(100dvh-6.5rem)] min-h-[30rem] w-full flex-col lg:h-[calc(100dvh-4rem)]">
        <div
            class="flex min-h-0 flex-1 overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

            {{-- Conversation list --}}
            <aside @class([
                'w-full shrink-0 flex-col border-e border-zinc-200 bg-zinc-50/70 dark:border-zinc-800 dark:bg-zinc-950/30 md:flex md:w-72 lg:w-80',
                'flex' => $activeConversationId === null && ! $composingNewChat,
                'hidden' => $activeConversationId !== null || $composingNewChat,
            ])>
                <div class="flex h-16 shrink-0 items-center justify-between border-b border-zinc-200 px-4 dark:border-zinc-800">
                    <div>
                        <h1 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ __('Conversations') }}
                        </h1>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">
                            {{ __('Your chats with the assistant') }}
                        </p>
                    </div>

                    <button type="button" wire:click="newConversation" title="{{ __('New conversation') }}"
                        class="flex size-9 items-center justify-center rounded-lg text-zinc-500 transition hover:bg-zinc-200/70 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                        </svg>
                        <span class="sr-only">{{ __('New conversation') }}</span>
                    </button>
                </div>

                <div class="shrink-0 border-b border-zinc-200 p-3 dark:border-zinc-800">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="pointer-events-none absolute start-3 top-1/2 size-4 -translate-y-1/2 text-zinc-400"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path stroke-linecap="round" d="m20 20-3.5-3.5" />
                        </svg>

                        <input type="search" wire:model.live.debounce.300ms="search"
                            placeholder="{{ __('Search conversations...') }}"
                            class="w-full rounded-xl border-0 bg-zinc-200/60 py-2.5 ps-9 pe-4 text-sm text-zinc-900 outline-none placeholder:text-zinc-400 focus:bg-white focus:ring-2 focus:ring-indigo-500 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:bg-zinc-800" />
                    </div>
                </div>

                <div class="min-h-0 flex-1 overflow-y-auto p-2">
                    @forelse ($this->conversations as $conversation)
                        <div wire:key="conversation-{{ $conversation['id'] }}" class="group relative">
                            <button type="button" wire:click="selectConversation('{{ $conversation['id'] }}')"
                                @class([
                                    'flex w-full items-start gap-3 rounded-xl p-3 pe-10 text-start transition',
                                    'bg-indigo-50 dark:bg-indigo-500/10' => $conversation['id'] === $activeConversationId,
                                    'hover:bg-zinc-200/60 dark:hover:bg-zinc-800/70' => $conversation['id'] !== $activeConversationId,
                                ])>
                                <span
                                    @class([
                                        'mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-full',
                                        'bg-indigo-600 text-white' => $conversation['id'] === $activeConversationId,
                                        'bg-zinc-200 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300' => $conversation['id'] !== $activeConversationId,
                                    ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm3.75 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm3.75 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 12c0 4.556-4.03 8.25-9 8.25a9.76 9.76 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                                    </svg>
                                </span>

                                <span class="min-w-0 flex-1">
                                    <span class="flex items-center justify-between gap-2">
                                        <span
                                            @class([
                                                'truncate text-sm font-semibold',
                                                'text-indigo-900 dark:text-indigo-200' => $conversation['id'] === $activeConversationId,
                                                'text-zinc-900 dark:text-zinc-100' => $conversation['id'] !== $activeConversationId,
                                            ])>
                                            {{ $conversation['title'] }}
                                        </span>

                                        <span class="shrink-0 text-[11px] text-zinc-400 dark:text-zinc-500">
                                            {{ $conversation['updated_at']->isToday() ? $conversation['updated_at']->format('g:i A') : $conversation['updated_at']->format('M j') }}
                                        </span>
                                    </span>

                                    <span class="mt-0.5 block truncate text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $conversation['preview'] ?: __('No messages yet') }}
                                    </span>
                                </span>
                            </button>

                            <button type="button" wire:click="deleteConversation('{{ $conversation['id'] }}')"
                                wire:confirm="{{ __('Delete this conversation? This cannot be undone.') }}"
                                title="{{ __('Delete conversation') }}"
                                class="absolute end-2 top-1/2 flex size-7 -translate-y-1/2 items-center justify-center rounded-lg text-zinc-400 opacity-0 transition hover:bg-red-50 hover:text-red-600 focus:opacity-100 group-hover:opacity-100 dark:hover:bg-red-500/10 dark:hover:text-red-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                <span class="sr-only">{{ __('Delete conversation') }}</span>
                            </button>
                        </div>
                    @empty
                        <div class="px-3 py-8 text-center">
                            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-300">
                                {{ $search !== '' ? __('No matching conversations') : __('No conversations yet') }}
                            </p>
                            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $search !== '' ? __('Try a different search term.') : __('Start a new chat to get going.') }}
                            </p>
                        </div>
                    @endforelse
                </div>

                <div class="shrink-0 border-t border-zinc-200 p-3 dark:border-zinc-800">
                    <div class="flex items-center gap-3 rounded-xl px-1 py-1">
                        <span
                            class="flex size-9 shrink-0 items-center justify-center rounded-full bg-zinc-200 text-xs font-semibold text-zinc-600 dark:bg-zinc-700 dark:text-zinc-200">
                            {{ auth()->user()->initials() }}
                        </span>

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="truncate text-xs text-zinc-500 dark:text-zinc-400">
                                {{ __('Signed in') }}
                            </p>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Chat window --}}
            <main @class([
                'min-w-0 flex-1 flex-col bg-white dark:bg-zinc-900 md:flex',
                'flex' => $activeConversationId !== null || $composingNewChat,
                'hidden' => $activeConversationId === null && ! $composingNewChat,
            ])>
                {{-- Header --}}
                <header
                    class="flex h-16 shrink-0 items-center justify-between gap-3 border-b border-zinc-200 px-4 dark:border-zinc-800 sm:px-5">
                    <div class="flex min-w-0 items-center gap-3">
                        <button type="button" wire:click="closeConversation"
                            class="flex size-9 shrink-0 items-center justify-center rounded-lg text-zinc-500 transition hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100 md:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span class="sr-only">{{ __('Back to conversations') }}</span>
                        </button>

                        <span
                            class="flex size-9 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" />
                            </svg>
                        </span>

                        <div class="min-w-0">
                            <h2 class="truncate text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ filled($this->activeConversation['title'] ?? null) ? $this->activeConversation['title'] : __('New conversation') }}
                            </h2>
                            <p class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400">
                                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                {{ __('AI assistant') }}
                            </p>
                        </div>
                    </div>

                    <button type="button" wire:click="newConversation" title="{{ __('New conversation') }}"
                        class="flex size-9 shrink-0 items-center justify-center rounded-lg text-zinc-500 transition hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                        </svg>
                        <span class="sr-only">{{ __('New conversation') }}</span>
                    </button>
                </header>

                {{-- Messages --}}
                <div id="chat-messages" x-data
                    x-init="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
                    x-on:chat-updated.window="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
                    class="min-h-0 flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                    <div class="mx-auto flex h-full max-w-3xl flex-col">
                        @if ($this->activeMessages->isEmpty())
                            <div class="flex flex-1 flex-col items-center justify-center py-8 text-center">
                                <span
                                    class="flex size-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" />
                                    </svg>
                                </span>

                                <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                    {{ __('How can I help you today?') }}
                                </h3>
                                <p class="mt-1 max-w-md text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ __('Ask a question, brainstorm an idea, or paste something you would like help with.') }}
                                </p>

                                <div class="mt-6 grid w-full max-w-lg gap-2 sm:grid-cols-2">
                                    @foreach ([
                                        'Explain a complex topic in simple terms',
                                        'Help me debug a piece of code',
                                        'Write a short professional email',
                                        'Brainstorm ideas for a side project',
                                    ] as $suggestion)
                                        <button type="button" wire:click='fillPrompt(@js($suggestion))'
                                            class="rounded-xl border border-zinc-200 px-4 py-3 text-start text-sm text-zinc-600 transition hover:border-indigo-300 hover:bg-indigo-50/50 hover:text-zinc-900 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-indigo-500/50 dark:hover:bg-indigo-500/5 dark:hover:text-zinc-100">
                                            {{ $suggestion }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="space-y-5">
                                @foreach ($this->activeMessages as $chatMessage)
                                    <div wire:key="message-{{ $chatMessage['id'] }}"
                                        @class([
                                            'flex items-start gap-3',
                                            'justify-end' => $chatMessage['role'] === 'user',
                                        ])>
                                        @if ($chatMessage['role'] === 'assistant')
                                            <span
                                                class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" />
                                                </svg>
                                            </span>
                                        @endif

                                        <div @class([
                                            'max-w-[85%] rounded-2xl px-4 py-2.5 text-sm leading-6 shadow-sm sm:max-w-[75%]',
                                            'bg-indigo-600 text-white' => $chatMessage['role'] === 'user',
                                            'bg-zinc-100 text-zinc-900 ring-1 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-100 dark:ring-zinc-700' => $chatMessage['role'] === 'assistant',
                                        ])>
                                            @if ($chatMessage['role'] === 'assistant')
                                                <div
                                                    class="space-y-2 break-words [&_a]:text-indigo-500 [&_a]:underline [&_blockquote]:border-s-2 [&_blockquote]:border-zinc-300 [&_blockquote]:ps-3 [&_blockquote]:italic [&_code]:rounded [&_code]:bg-black/5 [&_code]:px-1 [&_code]:py-0.5 [&_code]:text-[13px] dark:[&_code]:bg-white/10 [&_h1]:text-base [&_h1]:font-semibold [&_h2]:text-sm [&_h2]:font-semibold [&_h3]:text-sm [&_h3]:font-semibold [&_li]:my-0.5 [&_ol]:list-decimal [&_ol]:ps-5 [&_pre]:overflow-x-auto [&_pre]:rounded-lg [&_pre]:bg-zinc-900 [&_pre]:p-3 [&_pre]:text-zinc-100 dark:[&_pre]:bg-black/40 [&_pre_code]:bg-transparent [&_pre_code]:p-0 [&_strong]:font-semibold [&_ul]:list-disc [&_ul]:ps-5">
                                                    {!! Str::markdown($chatMessage['content'], [
                                                        'html_input' => 'strip',
                                                        'allow_unsafe_links' => false,
                                                    ]) !!}
                                                </div>
                                            @else
                                                <p class="whitespace-pre-wrap break-words">
                                                    {{ $chatMessage['content'] }}
                                                </p>
                                            @endif

                                            <div @class([
                                                'mt-1 text-[10px]',
                                                'text-indigo-200' => $chatMessage['role'] === 'user',
                                                'text-zinc-400 dark:text-zinc-500' => $chatMessage['role'] === 'assistant',
                                            ])>
                                                {{ $chatMessage['created_at']?->format('g:i A') }}
                                            </div>
                                        </div>

                                        @if ($chatMessage['role'] === 'user')
                                            <span
                                                class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-full bg-zinc-200 text-[11px] font-semibold text-zinc-600 dark:bg-zinc-700 dark:text-zinc-200">
                                                {{ auth()->user()->initials() }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Thinking indicator --}}
                        <div wire:loading.flex wire:target="send" style="display: none;" class="mt-5 items-start gap-3">
                            <span
                                class="flex size-8 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" />
                                </svg>
                            </span>

                            <div
                                class="rounded-2xl rounded-tl-md bg-zinc-100 px-4 py-3 ring-1 ring-zinc-200 dark:bg-zinc-800 dark:ring-zinc-700">
                                <span class="flex items-center gap-1">
                                    <span class="size-1.5 animate-bounce rounded-full bg-zinc-400 [animation-delay:-0.3s]"></span>
                                    <span class="size-1.5 animate-bounce rounded-full bg-zinc-400 [animation-delay:-0.15s]"></span>
                                    <span class="size-1.5 animate-bounce rounded-full bg-zinc-400"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Composer --}}
                <footer class="shrink-0 border-t border-zinc-200 bg-white p-3 dark:border-zinc-800 dark:bg-zinc-900 sm:p-4">
                    <div class="mx-auto max-w-3xl">
                        @if ($errorMessage)
                            <div
                                class="mb-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300">
                                {{ $errorMessage }}
                            </div>
                        @endif

                        @error('message')
                            <p class="mb-3 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror

                        <form wire:submit="send" class="flex items-end gap-2">
                            <div
                                class="flex min-h-11 flex-1 items-end rounded-2xl bg-zinc-100 px-4 py-2.5 ring-1 ring-inset ring-zinc-200 focus-within:ring-2 focus-within:ring-indigo-500 dark:bg-zinc-800 dark:ring-zinc-700">
                                <textarea wire:model="message" rows="1" x-data
                                    x-init="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 160) + 'px'"
                                    x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 160) + 'px'"
                                    x-on:keydown.enter="if (! $event.shiftKey) { $event.preventDefault(); $wire.send(); }"
                                    placeholder="{{ __('Type a message...') }}"
                                    class="max-h-40 min-h-6 flex-1 resize-none border-0 bg-transparent p-0 text-sm leading-6 text-zinc-900 outline-none placeholder:text-zinc-400 focus:ring-0 dark:text-zinc-100"></textarea>
                            </div>

                            <button type="submit" wire:loading.attr="disabled" wire:target="send"
                                class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60">
                                <svg wire:loading.remove wire:target="send" xmlns="http://www.w3.org/2000/svg"
                                    class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12 3.27 3.125A59.769 59.769 0 0 1 21.485 12 59.769 59.769 0 0 1 3.27 20.875L6 12Zm0 0h7.5" />
                                </svg>
                                <svg wire:loading wire:target="send" xmlns="http://www.w3.org/2000/svg"
                                    class="size-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4Z"></path>
                                </svg>
                                <span class="sr-only">{{ __('Send message') }}</span>
                            </button>
                        </form>

                        <p class="mt-2 text-center text-[11px] text-zinc-400 dark:text-zinc-500">
                            {{ __('Enter to send · Shift + Enter for a new line') }}
                        </p>
                    </div>
                </footer>
            </main>
        </div>
    </div>
