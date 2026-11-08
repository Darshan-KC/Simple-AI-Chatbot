<?php

use App\Ai\Agents\ChatAgent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laravel\Ai\Models\Conversation;
use Laravel\Ai\Models\ConversationMessage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

return new #[Title('Chat')] class extends Component
{
    public ?string $activeConversationId = null;

    public string $search = '';

    public string $message = '';

    public bool $composingNewChat = false;

    public ?string $errorMessage = null;

    public function mount(): void
    {
        $this->activeConversationId = $this->conversations()->first()['id'] ?? null;
    }

    /**
     * @return Collection<int, array{id: string, title: string, updated_at: Carbon, preview: string}>
     */
    #[Computed]
    public function conversations(): Collection
    {
        $user = auth()->user();

        if ($user === null) {
            return collect();
        }

        $conversations = Conversation::query()
            ->where('participant_type', $user->getMorphClass())
            ->where('participant_id', $user->getKey())
            ->when(trim($this->search) !== '', function ($query): void {
                $query->where('title', 'like', '%'.trim($this->search).'%');
            })
            ->latest('updated_at')
            ->limit(50)
            ->get();

        $previews = ConversationMessage::query()
            ->whereIn('conversation_id', $conversations->pluck('id'))
            ->whereIn('role', ['user', 'assistant'])
            ->orderBy('created_at')
            ->get(['conversation_id', 'content'])
            ->groupBy('conversation_id');

        return $conversations->map(fn (Conversation $conversation): array => [
            'id' => $conversation->id,
            'title' => $conversation->title,
            'updated_at' => $conversation->updated_at,
            'preview' => (string) ($previews->get($conversation->id)?->last()?->content ?? ''),
        ])->values();
    }

    /**
     * @return Collection<int, array{id: string, role: string, content: string, created_at: Carbon|null}>
     */
    #[Computed]
    public function activeMessages(): Collection
    {
        if ($this->activeConversationId === null || ! $this->ownsConversation($this->activeConversationId)) {
            return collect();
        }

        return ConversationMessage::query()
            ->where('conversation_id', $this->activeConversationId)
            ->whereIn('role', ['user', 'assistant'])
            ->orderBy('created_at')
            ->get()
            ->filter(fn (ConversationMessage $message): bool => filled($message->content))
            ->map(fn (ConversationMessage $message): array => [
                'id' => $message->id,
                'role' => $message->role,
                'content' => (string) $message->content,
                'created_at' => $message->created_at,
            ])
            ->values();
    }

    /**
     * @return array{id: string, title: string, updated_at: Carbon, preview: string}|null
     */
    #[Computed]
    public function activeConversation(): ?array
    {
        if ($this->activeConversationId === null || ! $this->ownsConversation($this->activeConversationId)) {
            return null;
        }

        $conversation = Conversation::query()->whereKey($this->activeConversationId)->first();

        return $conversation === null ? null : [
            'id' => $conversation->id,
            'title' => $conversation->title,
            'updated_at' => $conversation->updated_at,
            'preview' => '',
        ];
    }

    public function selectConversation(string $conversationId): void
    {
        if (! $this->ownsConversation($conversationId)) {
            abort(404);
        }

        $this->activeConversationId = $conversationId;
        $this->composingNewChat = false;
        $this->message = '';
        $this->errorMessage = null;
    }

    public function newConversation(): void
    {
        $this->activeConversationId = null;
        $this->composingNewChat = true;
        $this->message = '';
        $this->errorMessage = null;
    }

    public function closeConversation(): void
    {
        $this->activeConversationId = null;
        $this->composingNewChat = false;
    }

    public function deleteConversation(string $conversationId): void
    {
        if (! $this->ownsConversation($conversationId)) {
            abort(404);
        }

        ConversationMessage::query()->where('conversation_id', $conversationId)->delete();
        Conversation::query()->whereKey($conversationId)->delete();

        if ($this->activeConversationId === $conversationId) {
            $this->closeConversation();
        }
    }

    public function fillPrompt(string $prompt): void
    {
        $this->message = $prompt;
    }

    public function send(): void
    {
        $this->errorMessage = null;

        $this->validate([
            'message' => ['required', 'string', 'max:4000'],
        ]);

        if ($this->activeConversationId !== null && ! $this->ownsConversation($this->activeConversationId)) {
            $this->activeConversationId = null;
        }

        try {
            $response = (new ChatAgent)
                ->continueOrStart($this->activeConversationId, as: auth()->user())
                ->prompt(trim($this->message));

            $this->activeConversationId = $response->conversationId ?? $this->activeConversationId;
            $this->composingNewChat = false;
            $this->message = '';
            $this->dispatch('chat-updated');
        } catch (Throwable $exception) {
            report($exception);

            $this->errorMessage = __('The assistant could not respond right now. Please check the AI configuration and try again.');
        }
    }

    protected function ownsConversation(string $conversationId): bool
    {
        $user = auth()->user();

        if ($user === null) {
            return false;
        }

        return Conversation::query()
            ->whereKey($conversationId)
            ->where('participant_type', $user->getMorphClass())
            ->where('participant_id', $user->getKey())
            ->exists();
    }
};
