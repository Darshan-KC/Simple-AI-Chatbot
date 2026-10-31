<?php

use App\Livewire\Chat\Chat;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Route::livewire('/chat', 'pages::chat')->name('chat');
    // Route::get('/chat', Chat::class)->name('chat');
    Route::livewire('/chat', 'pages::⚡chat.chat')->name('chat');
});

require __DIR__.'/settings.php';
