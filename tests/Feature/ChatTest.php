<?php

use App\Ai\Agents\ChatAgent;
use App\Models\User;
use Laravel\Ai\Models\Conversation;
use Laravel\Ai\Models\ConversationMessage;
use Livewire\Livewire;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('chat'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the chat page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('chat'));

    $response->assertOk();
});

test('sending a message creates a conversation and stores both messages', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    ChatAgent::fake(['Hello! How can I help you today?']);

    Livewire::test('pages::chat')
        ->set('message', 'What is Laravel?')
        ->call('send')
        ->assertHasNoErrors()
        ->assertSee('Hello! How can I help you today?')
        ->assertSet('message', '');

    $conversation = Conversation::query()->sole();

    expect($conversation->participant_id)->toBe($user->id);

    $messages = ConversationMessage::query()
        ->where('conversation_id', $conversation->id)
        ->orderBy('created_at')
        ->get();

    expect($messages->pluck('role')->all())->toBe(['user', 'assistant'])
        ->and($messages->first()->content)->toBe('What is Laravel?')
        ->and($messages->last()->content)->toBe('Hello! How can I help you today?');
});

test('an empty message is rejected without prompting the assistant', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    ChatAgent::fake();

    Livewire::test('pages::chat')
        ->set('message', '   ')
        ->call('send')
        ->assertHasErrors(['message' => 'required']);

    ChatAgent::assertNeverPrompted();
    expect(Conversation::query()->count())->toBe(0);
});

test('a user cannot open another users conversation', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $conversationId = Conversation::query()->create([
        'id' => (string) str()->uuid(),
        'participant_type' => $otherUser->getMorphClass(),
        'participant_id' => $otherUser->getKey(),
        'title' => 'Private conversation',
    ])->id;

    $this->actingAs($user);

    Livewire::test('pages::chat')
        ->call('selectConversation', $conversationId)
        ->assertNotFound();
});

test('deleting a conversation removes it and its messages', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    ChatAgent::fake(['A reply.']);

    $component = Livewire::test('pages::chat')
        ->set('message', 'Hello')
        ->call('send');

    $conversation = Conversation::query()->sole();
    $conversationId = $conversation->id;

    $component->call('deleteConversation', $conversationId);

    $this->assertModelMissing($conversation);
    expect(ConversationMessage::query()->where('conversation_id', $conversationId)->count())->toBe(0);
});
