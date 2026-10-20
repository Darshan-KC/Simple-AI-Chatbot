<?php

use Livewire\Component;

new class extends Component
{
    public ?int $activeConversationId = 1;

    public string $search = '';

    public string $message = '';

    public function selectConversation(int $conversationId): void
    {
        $this->activeConversationId = $conversationId;
    }

    public function closeConversation(): void
    {
        $this->activeConversationId = null;
    }
    
    public function render()
    {
        return view('pages.⚡chat.chat');
    }
};