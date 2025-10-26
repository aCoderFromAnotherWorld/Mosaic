# Mosaic

Mosaic is a modern social networking platform built with Laravel 12 that helps communities share stories, react in real time, and grow meaningful connections. From rich media posts to direct messaging, Mosaic delivers a polished end-to-end experience for creators and their audiences.

## Project Overview

Mosaic combines the familiar patterns of contemporary social apps with a clean Laravel architecture. Authenticated users can publish posts with media, join conversations through threaded comments and reactions, manage friendships, exchange private messages, and stay informed with contextual notifications—all inside a responsive interface powered by Blade, Tailwind, and Alpine tooling.

## Key Features

-   Dynamic feed with shareable posts, rich reactions, and threaded comments (including comment likes).
-   Profile management with editable bios, avatars, and quick navigation to user timelines.
-   Follow and friend flows to tailor the social graph and grant deeper access to content.
-   Private conversations with conversation history, message editing, and deletion.
-   Actionable notifications with unread counts, mark-all, and contextual redirect actions.
-   Global search that surfaces people and content across the platform.
-   Accessible UI components backed by Laravel Breeze scaffolding and Tailwind CSS styling.

## Technology Stack

-   **Backend:** Laravel 12, PHP 8.2, Eloquent ORM, Form Request validation, Policies.
-   **Frontend:** Blade templates, Tailwind CSS, Alpine-ready components, Vite asset pipeline.
-   **Data:** MySQL, Laravel migrations & seeders, relationship-rich models.
-   **Tooling:** Composer scripts, Laravel artisan commands, npm/Vite workflow, Pest-ready PHPUnit.

## Getting Started

### Prerequisites

-   PHP 8.2+ with required extensions (`pdo_sqlite`, `mbstring`, `openssl`, etc.).
-   Composer 2.6+.
-   Node.js 18+ and npm 9+ (or pnpm/yarn if preferred).
-   SQLite (default) or another database supported by Laravel.

### Installation

```bash
git clone https://github.com/aCoderFromAnotherWorld/Mosaic mosaic
cd mosaic
composer install
npm install
```

1. Copy the example environment file and generate an application key:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

2. Configure your `.env` values (database, mail, queue, storage disks). SQLite works out of the box; otherwise update the connection settings.

3. Prepare the database:

    ```bash
    php artisan migrate --seed
    ```

    The seed data provisions three demo users (`password` for each) with sample posts, reactions, and conversations to explore the UI.

### Local Development

-   Start the full development stack (Laravel server, queue listener, logs, and Vite):

    ```bash
    composer run dev
    ```

-   Alternatively, run services individually:

    ```bash
    php artisan serve
    php artisan queue:listen
    npm run dev
    ```

Visit `http://localhost:8000` to sign in and explore the feed.

## Testing & Quality

-   Run the backend and feature tests:

    ```bash
    composer test
    ```

-   Compile assets to verify the frontend build:

    ```bash
    npm run build
    ```

-   Format PHP code with Laravel Pint:

    ```bash
    ./vendor/bin/pint
    ```

## Project Structure Highlights

-   `app/Http/Controllers`: Feature-driven controllers for feed, posts, reactions, comments, messaging, friends, and notifications.
-   `app/Models`: Rich domain models (posts, comments, conversations, reactions) with policy guards for secure interactions.
-   `resources/views`: Blade templates organized by feature (`feed`, `posts`, `messages`, `profile`) plus reusable components and layouts.
-   `database/migrations`: Schema for social graph tables (follows, friends, reactions, notifications, messaging).
-   `database/seeders`: Opinionated demo data to showcase platform capabilities.

## Operational Notes

-   Queue workers power notification delivery and other background jobs; run `php artisan queue:listen` during development.
-   Media uploads use the configured filesystem disk. Update `FILESYSTEM_DISK` in `.env` to point at local or cloud storage.
-   To reset demo data, run `php artisan migrate:fresh --seed`.

## License

Mosaic is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
