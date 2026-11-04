<div class="h-[calc(100vh-4rem)] min-h-0 p-4 sm:p-6">
    <div class="mx-auto flex h-full max-w-7xl overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- Sidebar --}}
        <aside @class([
            'w-full shrink-0 flex-col border-r border-gray-200 bg-white md:flex md:w-80 lg:w-96',
            'flex' => $activeConversationId === null && ! $composingNewChat,
            'hidden' => $activeConversationId !== null || $composingNewChat,
        ])>
            <div class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 px-5">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900">{{ __('Chats') }}</h1>
                    <p class="text-xs text-gray-500">{{ __('Your conversations') }}</p>
                </div>

                <button type="button" wire:click="newConversation" title="{{ __('New conversation') }}"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                    </svg>
                    <span class="sr-only">{{ __('New conversation') }}</span>
                </button>
            </div>

            <div class="shrink-0 border-b border-gray-100 p-3">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7" />
                        <path stroke-linecap="round" d="m20 20-3.5-3.5" />
                    </svg>

                    <input type="search" wire:model.live.debounce.300ms="search"
                        placeholder="{{ __('Search conversations...') }}"
                        class="w-full rounded-xl border-0 bg-gray-100 py-2.5 pl-9 pr-4 text-sm text-gray-900 outline-none ring-0 placeholder:text-gray-400 focus:bg-white focus:ring-2 focus:ring-indigo-500" />
                </div>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto">
                <div class="p-2">
                    @forelse ($this->conversations as $conversation)
                        <div wire:key="conversation-{{ $conversation['id'] }}" class="group relative">
                            <x-chat.conversation-item
                                :name="$conversation['title']"
                                :message="$conversation['preview'] ?: __('No messages yet')"
                                :time="$conversation['updated_at']->isToday() ? $conversation['updated_at']->format('g:i A') : $conversation['updated_at']->format('M j')"
                                :initials="\Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($conversation['title'], 0, 2))"
                                :active="$conversation['id'] === $activeConversationId"
                                wire:click="selectConversation('{{ $conversation['id'] }}')" />

                            <button type="button" wire:click="deleteConversation('{{ $conversation['id'] }}')"
                                wire:confirm="{{ __('Delete this conversation? This cannot be undone.') }}"
                                title="{{ __('Delete conversation') }}"
                                class="absolute right-2 top-1/2 flex h-7 w-7 -translate-y-1/2 items-center justify-center rounded-lg text-gray-400 opacity-0 transition hover:bg-red-50 hover:text-red-600 focus:opacity-100 group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                <span class="sr-only">{{ __('Delete conversation') }}</span>
                            </button>
                        </div>
                    @empty
                        <div class="px-3 py-8 text-center">
                            <p class="text-sm font-medium text-gray-600">
                                {{ $search !== '' ? __('No matching conversations') : __('No conversations yet') }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $search !== '' ? __('Try a different search term.') : __('Start a new chat to get going.') }}
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="shrink-0 border-t border-gray-200 p-3">
                <div class="flex items-center gap-3 rounded-xl px-2 py-2">
                    <x-chat.avatar :initials="auth()->user()->initials()" size="md" :online="true" />

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-gray-900">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="truncate text-xs text-gray-500">
                            {{ __('Signed in') }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Chat window --}}
        <main @class([
            'min-w-0 flex-1 flex-col bg-gray-50 md:flex',
            'flex' => $activeConversationId !== null || $composingNewChat,
            'hidden' => $activeConversationId === null && ! $composingNewChat,
        ])>
            <header class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-5">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" wire:click="closeConversation"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 md:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="sr-only">{{ __('Back to conversations') }}</span>
                    </button>

                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" />
                        </svg>
                    </span>

                    <div class="min-w-0">
                        <h2 class="truncate text-sm font-semibold text-gray-900">
                            {{ filled($this->activeConversation['title'] ?? null) ? $this->activeConversation['title'] : __('New conversation') }}
                        </h2>
                        <p class="flex items-center gap-1.5 text-xs text-gray-500">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                            {{ __('AI assistant') }}
                        </p>
                    </div>
                </div>

                <button type="button" wire:click="newConversation" title="{{ __('New conversation') }}"
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                    </svg>
                    <span class="sr-only">{{ __('New conversation') }}</span>
                </button>
            </header>

            <div id="chat-messages" x-data
                x-init="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
                x-on:chat-updated.window="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
                class="min-h-0 flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                <div class="mx-auto max-w-3xl">
                    @if ($this->activeMessages->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" />
                                </svg>
                            </span>

                            <h3 class="mt-4 text-lg font-semibold text-gray-900">
                                {{ __('How can I help you today?') }}
                            </h3>
                            <p class="mt-1 max-w-md text-sm text-gray-500">
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
                                        class="rounded-xl border border-gray-200 px-4 py-3 text-left text-sm text-gray-600 transition hover:border-indigo-300 hover:bg-indigo-50/50 hover:text-gray-900">
                                        {{ $suggestion }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <x-chat.date-separator :label="$this->activeMessages->first()['created_at']?->isToday() ? __('Today') : $this->activeMessages->first()['created_at']?->format('F j, Y')" />

                        <div class="space-y-4">
                            @foreach ($this->activeMessages as $chatMessage)
                                <div wire:key="message-{{ $chatMessage['id'] }}"
                                    @class([
                                        'flex items-end gap-2',
                                        'justify-end' => $chatMessage['role'] === 'user',
                                    ])>
                                    @if ($chatMessage['role'] === 'assistant')
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" />
                                            </svg>
                                        </span>
                                    @endif

                                    <div @class([
                                        'max-w-[85%] rounded-2xl px-4 py-2.5 text-sm leading-6 shadow-sm sm:max-w-[75%]',
                                        'bg-indigo-600 text-white' => $chatMessage['role'] === 'user',
                                        'bg-gray-100 text-gray-900 ring-1 ring-gray-200' => $chatMessage['role'] === 'assistant',
                                    ])>
                                        @if ($chatMessage['role'] === 'assistant')
                                            <div class="space-y-2 break-words [&_a]:text-indigo-600 [&_a]:underline [&_blockquote]:border-l-2 [&_blockquote]:border-gray-300 [&_blockquote]:pl-3 [&_blockquote]:italic [&_code]:rounded [&_code]:bg-black/5 [&_code]:px-1 [&_code]:py-0.5 [&_code]:text-[13px] [&_h1]:text-base [&_h1]:font-semibold [&_h2]:text-sm [&_h2]:font-semibold [&_h3]:text-sm [&_h3]:font-semibold [&_li]:my-0.5 [&_ol]:list-decimal [&_ol]:pl-5 [&_pre]:overflow-x-auto [&_pre]:rounded-lg [&_pre]:bg-gray-900 [&_pre]:p-3 [&_pre]:text-gray-100 [&_pre_code]:bg-transparent [&_pre_code]:p-0 [&_strong]:font-semibold [&_ul]:list-disc [&_ul]:pl-5">
                                                {!! \Illuminate\Support\Str::markdown($chatMessage['content'], [
                                                    'html_input' => 'strip',
                                                    'allow_unsafe_links' => false,
                                                ]) !!}
                                            </div>
                                        @else
                                            <p class="whitespace-pre-wrap break-words">{{ $chatMessage['content'] }}</p>
                                        @endif

                                        <div @class([
                                            'mt-1 text-[10px]',
                                            'text-indigo-200' => $chatMessage['role'] === 'user',
                                            'text-gray-400' => $chatMessage['role'] === 'assistant',
                                        ])>
                                            {{ $chatMessage['created_at']?->format('g:i A') }}
                                        </div>
                                    </div>

                                    @if ($chatMessage['role'] === 'user')
                                        <x-chat.avatar :initials="auth()->user()->initials()" size="sm" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div wire:loading.flex wire:target="send" style="display: none;" class="mt-4 items-end gap-2">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" />
                            </svg>
                        </span>

                        <div class="rounded-2xl rounded-bl-md bg-white px-4 py-3 shadow-sm ring-1 ring-gray-200">
                            <span class="flex items-center gap-1">
                                <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400 [animation-delay:-0.3s]"></span>
                                <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400 [animation-delay:-0.15s]"></span>
                                <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="shrink-0 border-t border-gray-200 bg-white p-3 sm:p-4">
                <div class="mx-auto max-w-3xl">
                    @if ($errorMessage)
                        <div class="mb-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errorMessage }}
                        </div>
                    @endif

                    @error('message')
                        <p class="mb-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <form wire:submit="send" class="flex items-end gap-2">
                        <div class="flex min-h-10 flex-1 items-end rounded-2xl bg-gray-100 px-4 py-2 focus-within:ring-2 focus-within:ring-indigo-500">
                            <textarea wire:model="message" rows="1" x-data
                                x-init="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 160) + 'px'"
                                x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 160) + 'px'"
                                x-on:keydown.enter="if (! $event.shiftKey) { $event.preventDefault(); $wire.send(); }"
                                placeholder="{{ __('Type a message...') }}"
                                class="max-h-40 min-h-6 flex-1 resize-none border-0 bg-transparent p-0 text-sm leading-6 text-gray-900 outline-none placeholder:text-gray-400 focus:ring-0"></textarea>
                        </div>

                        <button type="submit" wire:loading.attr="disabled" wire:target="send"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60"
                            title="{{ __('Send message') }}">
                            <svg wire:loading.remove wire:target="send" xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 12 3.27 3.125A59.769 59.769 0 0 1 21.485 12 59.769 59.769 0 0 1 3.27 20.875L6 12Zm0 0h7.5" />
                            </svg>
                            <svg wire:loading wire:target="send" xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4Z"></path>
                            </svg>
                            <span class="sr-only">{{ __('Send message') }}</span>
                        </button>
                    </form>

                    <p class="mt-2 text-center text-[11px] text-gray-400">
                        {{ __('Enter to send · Shift + Enter for a new line') }}
                    </p>
                </div>
            </footer>
        </main>
    </div>
</div>
