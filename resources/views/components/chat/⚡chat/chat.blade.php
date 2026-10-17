{{-- <div> --}}
{{-- He who is contented is rich. - Laozi --}}
{{-- </div> --}}

<div class="h-[calc(100vh-4rem)] min-h-0 p-4 sm:p-6">
    <div class="mx-auto flex h-full max-w-7xl overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- ========================================================= --}}
        {{-- SIDEBAR                                                   --}}
        {{-- ========================================================= --}}

        <aside class="flex w-full shrink-0 flex-col border-r border-gray-200 bg-white md:w-80 lg:w-96"
            :class="$wire.activeConversationId ? 'hidden md:flex' : 'flex'">
            {{-- Sidebar Header --}}
            <div class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 px-5">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900">
                        Chats
                    </h1>

                    <p class="text-xs text-gray-500">
                        Your conversations
                    </p>
                </div>

                <button type="button"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900"
                    title="New conversation">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                    </svg>
                </button>
            </div>

            {{-- Search --}}
            <div class="shrink-0 border-b border-gray-100 p-3">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7" />
                        <path stroke-linecap="round" d="m20 20-3.5-3.5" />
                    </svg>

                    <input type="search" wire:model.live="search" placeholder="Search conversations..."
                        class="w-full rounded-xl border-0 bg-gray-100 py-2.5 pl-9 pr-4 text-sm text-gray-900 outline-none ring-0 placeholder:text-gray-400 focus:bg-white focus:ring-2 focus:ring-indigo-500" />
                </div>
            </div>

            {{-- Conversation List --}}
            <div class="min-h-0 flex-1 overflow-y-auto">

                <div class="p-2">

                    <x-chat.conversation-item name="John Doe" message="Hey, how are you doing?" time="10:32 AM"
                        initials="JD" :unread="2" :online="true" :active="$activeConversationId === 1"
                        wire:click="selectConversation(1)" />

                    <x-chat.conversation-item name="Jane Smith" message="See you tomorrow!" time="9:41 AM"
                        initials="JS" :unread="0" :online="true" :active="$activeConversationId === 2"
                        wire:click="selectConversation(2)" />

                    <x-chat.conversation-item name="Alex Johnson" message="Thanks for your help." time="Yesterday"
                        initials="AJ" :unread="1" :online="false" :active="$activeConversationId === 3"
                        wire:click="selectConversation(3)" />

                    <x-chat.conversation-item name="Sarah Wilson" message="I'll check it and let you know."
                        time="Monday" initials="SW" :unread="0" :online="false" :active="$activeConversationId === 4"
                        wire:click="selectConversation(4)" />

                    <x-chat.conversation-item name="Michael Brown" message="Sounds good!" time="Sunday" initials="MB"
                        :unread="0" :online="true" :active="$activeConversationId === 5" wire:click="selectConversation(5)" />

                    <x-chat.conversation-item name="Development Team" message="Darshan: The deployment is ready."
                        time="Saturday" initials="DT" :unread="5" :online="false" :active="$activeConversationId === 6"
                        wire:click="selectConversation(6)" />

                </div>

            </div>

            {{-- Current User --}}
            <div class="shrink-0 border-t border-gray-200 p-3">
                <div class="flex items-center gap-3 rounded-xl px-2 py-2">
                    <x-chat.avatar initials="DC" size="md" :online="true" />

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-gray-900">
                            Darshan
                        </p>

                        <p class="truncate text-xs text-gray-500">
                            Online
                        </p>
                    </div>

                    <button type="button"
                        class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                        title="Settings">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </aside>


        {{-- ========================================================= --}}
        {{-- CHAT WINDOW                                                --}}
        {{-- ========================================================= --}}

        <main class="min-w-0 flex-1 flex-col bg-gray-50 md:flex"
            :class="$wire.activeConversationId ? 'flex' : 'hidden md:flex'">

            {{-- Chat Header --}}
            <header
                class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-5">

                <div class="flex min-w-0 items-center gap-3">

                    {{-- Mobile Back --}}
                    <button type="button" wire:click="$set('activeConversationId', null)"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 md:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div class="relative shrink-0">
                        <x-chat.avatar initials="JD" size="md" :online="true" />
                    </div>

                    <div class="min-w-0">
                        <h2 class="truncate text-sm font-semibold text-gray-900">
                            John Doe
                        </h2>

                        <p class="text-xs text-green-600">
                            Online
                        </p>
                    </div>

                </div>

                {{-- Header Actions --}}
                <div class="flex shrink-0 items-center gap-1">

                    {{-- Search --}}
                    <button type="button"
                        class="hidden rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 sm:block"
                        title="Search messages">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path stroke-linecap="round" d="m20 20-3.5-3.5" />
                        </svg>
                    </button>

                    {{-- Call --}}
                    <button type="button"
                        class="hidden rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 sm:block"
                        title="Call">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 0 0-1.173.417l-.97 1.293a1.125 1.125 0 0 1-1.21.38 12.035 12.035 0 0 1-7.136-7.136 1.125 1.125 0 0 1 .38-1.21l1.293-.97c.363-.272.534-.732.417-1.173L6.38 3.109A1.125 1.125 0 0 0 5.29 2.25H3.75A2.25 2.25 0 0 0 1.5 4.5v2.25Z" />
                        </svg>
                    </button>

                    {{-- More --}}
                    <button type="button"
                        class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900"
                        title="More">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                        </svg>
                    </button>

                </div>
            </header>


            {{-- ===================================================== --}}
            {{-- MESSAGE AREA                                           --}}
            {{-- ===================================================== --}}

            <div class="min-h-0 flex-1 overflow-y-auto px-4 py-6 sm:px-6">

                <div class="mx-auto max-w-3xl">

                    {{-- Date --}}
                    <x-chat.date-separator label="Today" />

                    {{-- Incoming --}}
                    <div class="mb-4 flex items-end gap-2">
                        <x-chat.avatar initials="JD" size="sm" />

                        <x-chat.message message="Hey! How are you doing?" time="10:28 AM" />
                    </div>

                    {{-- Incoming --}}
                    <div class="mb-4 flex items-end gap-2">
                        <x-chat.avatar initials="JD" size="sm" />

                        <x-chat.message message="I wanted to talk to you about the new project." time="10:29 AM" />
                    </div>

                    {{-- Outgoing --}}
                    <x-chat.message message="Hey John! I'm doing great. Sure, what's up?" time="10:30 AM"
                        :outgoing="true" />

                    {{-- Incoming --}}
                    <div class="mb-4 flex items-end gap-2">
                        <x-chat.avatar initials="JD" size="sm" />

                        <x-chat.message message="We're planning to start the backend implementation next week."
                            time="10:31 AM" />
                    </div>

                    {{-- Outgoing --}}
                    <x-chat.message message="Sounds good! I've already started looking into the architecture."
                        time="10:31 AM" :outgoing="true" />

                    {{-- Outgoing --}}
                    <x-chat.message message="I'll share my notes with you later today. 😊" time="10:32 AM"
                        :outgoing="true" :read="true" />

                    {{-- Typing --}}
                    <div class="mt-2 flex items-end gap-2">
                        <x-chat.avatar initials="JD" size="sm" />

                        <x-chat.typing-indicator />
                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- MESSAGE COMPOSER                                       --}}
            {{-- ===================================================== --}}

            <footer class="shrink-0 border-t border-gray-200 bg-white p-3 sm:p-4">

                <div class="mx-auto max-w-3xl">

                    <div class="flex items-end gap-2">

                        {{-- Attachment --}}
                        <button type="button"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-gray-500 transition hover:bg-gray-100 hover:text-gray-700"
                            title="Attach file">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l9.193-9.193a3 3 0 0 1 4.243 4.243l-9.193 9.193a1.5 1.5 0 0 1-2.121-2.121l8.486-8.486" />
                            </svg>
                        </button>

                        {{-- Input --}}
                        <div class="flex min-h-10 flex-1 items-end rounded-2xl bg-gray-100 px-4 py-2">

                            <textarea wire:model="message" rows="1" placeholder="Type a message..."
                                class="max-h-32 min-h-6 flex-1 resize-none border-0 bg-transparent p-0 text-sm leading-6 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-0"></textarea>

                            {{-- Emoji --}}
                            <button type="button"
                                class="ml-2 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-200"
                                title="Emoji">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9" />
                                    <path stroke-linecap="round" d="M8 14s1.5 2 4 2 4-2 4-2" />
                                    <path stroke-linecap="round" d="M9 9h.01M15 9h.01" />
                                </svg>
                            </button>

                        </div>

                        {{-- Send --}}
                        <button type="button"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white transition hover:bg-indigo-700"
                            title="Send message">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 12 3.27 3.125A59.769 59.769 0 0 1 21.485 12 59.769 59.769 0 0 1 3.27 20.875L6 12Zm0 0h7.5" />
                            </svg>
                        </button>

                    </div>

                </div>

            </footer>

        </main>
    </div>
</div>
