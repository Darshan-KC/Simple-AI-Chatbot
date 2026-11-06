# Simple AI Chatbot

A simple, multi-conversation AI chatbot built with Laravel, Livewire, and the [Laravel AI SDK](https://laravel.com/docs/ai). It ships with authentication, a responsive chat UI, persistent conversation history, and support for multiple AI providers.

## Features

- Chat with an AI assistant powered by `laravel/ai`
- Persistent conversations with automatic titles
- Conversation list with search, previews, and deletion
- Markdown rendering for assistant responses (code blocks, lists, links, etc.)
- Responsive two-pane chat layout with mobile support
- Authentication with login, registration, email verification, 2FA, and passkeys (Fortify)
- User settings for profile, appearance, and security
- Configurable AI provider (Gemini by default)

## Tech Stack

- PHP 8.3+
- Laravel 13
- Livewire 4 + Flux UI
- Tailwind CSS 4 / Vite
- Laravel AI SDK (`laravel/ai`)
- Pest, Pint, and Larastan for testing and static analysis

## Requirements

- PHP 8.3 or newer
- Composer
- Node.js and npm
- A database (SQLite by default, MySQL/PostgreSQL supported)
- An API key for your AI provider (Gemini by default)

## Installation

```bash
# Clone the repository
git clone <repository-url> simple-ai-chatbot
cd simple-ai-chatbot

# Install dependencies and set up the app
composer run setup
```

The `setup` script installs dependencies, creates `.env`, generates the application key, runs migrations, installs JavaScript dependencies, and builds frontend assets.

If you prefer to do it manually:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

### Database

SQLite is used by default. Make sure `database/database.sqlite` exists, or update the `DB_*` variables in `.env` to use another database.

```bash
touch database/database.sqlite
php artisan migrate
```

## Configuration

### AI Provider

The default provider is configured in `config/ai.php`. Gemini is selected by default:

```env
AI_DEFAULT_PROVIDER=gemini
GEMINI_API_KEY=your-api-key-here
```

Add your API key to `.env`, then clear the config cache:

```bash
php artisan config:clear
```

To use a different provider, change `AI_DEFAULT_PROVIDER` and set the corresponding key, for example:

```env
AI_DEFAULT_PROVIDER=openai
OPENAI_API_KEY=your-api-key-here
```

Supported providers include Anthropic, Azure, Bedrock, Cohere, DeepSeek, ElevenLabs, Gemini, Groq, Jina, Mistral, Ollama, OpenAI, OpenRouter, VoyageAI, and xAI. See `config/ai.php` for the full list.

### Mail

Mail is logged by default (`MAIL_MAILER=log`). Configure a real mailer in `.env` if you need email verification or password reset emails delivered.

## Usage

Start the development environment:

```bash
composer run dev
```

This runs the Laravel server, queue listener, and Vite dev server together. Alternatively, use separate terminals:

```bash
php artisan serve
npm run dev
```

Then visit the application, register an account, and open `/chat` to start a conversation.

### Chat Behavior

- **New chat** starts a fresh conversation.
- **Enter** sends a message; **Shift + Enter** inserts a new line.
- Conversations are scoped to the signed-in user and cannot be accessed by other users.
- Deleting a conversation permanently removes it and its messages.

## Development Commands

| Command | Description |
| --- | --- |
| `composer run dev` | Run Laravel, queue, and Vite dev servers |
| `npm run dev` | Start the Vite dev server |
| `npm run build` | Build frontend assets for production |
| `php artisan serve` | Serve the application |
| `composer test` | Run lint check, static analysis, and tests |
| `php artisan test --compact` | Run the test suite |
| `vendor/bin/pint --dirty` | Format changed PHP files |
| `composer types:check` | Run PHPStan/Larastan |
| `php artisan pail` | Tail application logs |

## Project Structure

```
app/
  Ai/Agents/ChatAgent.php          # AI agent, instructions, and tools
  Livewire/Settings/               # Profile, appearance, security components
  Models/User.php                  # User model
resources/views/
  pages/⚡chat/chat.php             # Chat Livewire component
  layouts/                         # App and auth layouts
  welcome.blade.php                # Landing page
config/ai.php                      # AI providers and defaults
database/migrations/               # App and AI conversation tables
routes/web.php                     # Web routes
routes/settings.php                # Settings routes
tests/Feature/ChatTest.php         # Chat feature tests
```

## Testing

```bash
php artisan test --compact
```

The chat tests fake the AI agent, so no provider API key is required to run the suite.

## AI Agent Customization

The assistant's behavior is defined in `app/Ai/Agents/ChatAgent.php`:

- `instructions()` — system prompt and response style
- `tools()` — tools available to the agent (empty by default)

Modify this file to change the assistant's personality, add tools, or tailor responses to your use case.

## License

This project is based on the Laravel Livewire starter kit and is released under the [MIT license](https://opensource.org/licenses/MIT).


## Contributing
Contributions are welcome! Please fork the repository and submit a pull request with your changes. Make sure to follow the existing code style and include tests for any new features or bug fixes.