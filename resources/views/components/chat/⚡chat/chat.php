<?php

// use Livewire\Component;

// new class extends Component
// {
//     //
// };

namespace App\Livewire\Chat;

use Livewire\Component;

class Chat extends Component
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

    public function send(): void
    {
        // Handle sending the message
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.chat.chat')
            ->layout('components.layouts.app');
    }
}