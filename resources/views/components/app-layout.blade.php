<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mosaic') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen">
        @include('layouts.navigation')

        @isset($header)
            <header class="border-b border-gray-200 bg-white">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="pt-4 sm:pt-6">
            {{ $slot }}
        </main>
    </div>

    <script>
        (() => {
            const DROPDOWN_SELECTOR = '[data-dropdown]';
            const TRIGGER_SELECTOR = '[data-dropdown-trigger]';
            const CONTENT_SELECTOR = '[data-dropdown-content]';
            const ITEM_SELECTOR = '[data-dropdown-item]';

            const dropdownState = new WeakMap();

            const getDropdownElements = (dropdown) => {
                if (dropdownState.has(dropdown)) {
                    return dropdownState.get(dropdown);
                }

                const trigger = dropdown.querySelector(TRIGGER_SELECTOR);
                const content = dropdown.querySelector(CONTENT_SELECTOR);
                const items = content ? Array.from(content.querySelectorAll(ITEM_SELECTOR)) : [];

                const data = { trigger, content, items };
                dropdownState.set(dropdown, data);

                return data;
            };

            const closeDropdown = (dropdown) => {
                const { trigger, content } = getDropdownElements(dropdown);
                if (!trigger || !content) {
                    return;
                }

                content.classList.add('hidden');
                trigger.setAttribute('aria-expanded', 'false');
                trigger.classList.remove('bg-gray-100');
            };

            const closeAllDropdowns = (exception = null) => {
                document.querySelectorAll(DROPDOWN_SELECTOR).forEach((dropdown) => {
                    if (exception && dropdown === exception) {
                        return;
                    }
                    closeDropdown(dropdown);
                });
            };

            const focusItem = (items, index) => {
                if (!items.length) {
                    return;
                }

                const clampedIndex = ((index % items.length) + items.length) % items.length;
                const item = items[clampedIndex];

                if (item) {
                    item.focus();
                    item.dataset.dropdownFocusIndex = clampedIndex;
                }
            };

            const openDropdown = (dropdown, { focus = null } = {}) => {
                const { trigger, content, items } = getDropdownElements(dropdown);
                if (!trigger || !content) {
                    return;
                }

                closeAllDropdowns(dropdown);

                content.classList.remove('hidden');
                trigger.setAttribute('aria-expanded', 'true');
                trigger.classList.add('bg-gray-100');

                if (Array.isArray(items) && items.length) {
                    if (focus === 'first') {
                        focusItem(items, 0);
                    } else if (focus === 'last') {
                        focusItem(items, items.length - 1);
                    }
                }
            };

            const handleTriggerKeyDown = (event, dropdown) => {
                switch (event.key) {
                    case 'Enter':
                    case ' ':
                        event.preventDefault();
                        openDropdown(dropdown, { focus: 'first' });
                        break;
                    case 'ArrowDown':
                        event.preventDefault();
                        openDropdown(dropdown, { focus: 'first' });
                        break;
                    case 'ArrowUp':
                        event.preventDefault();
                        openDropdown(dropdown, { focus: 'last' });
                        break;
                    case 'Escape':
                        closeDropdown(dropdown);
                        break;
                }
            };

            const handleContentKeyDown = (event, dropdown) => {
                const { items, trigger } = getDropdownElements(dropdown);
                if (!items.length) {
                    return;
                }

                const currentIndex = items.findIndex((item) => item === document.activeElement);

                switch (event.key) {
                    case 'ArrowDown':
                        event.preventDefault();
                        focusItem(items, currentIndex + 1);
                        break;
                    case 'ArrowUp':
                        event.preventDefault();
                        focusItem(items, currentIndex - 1);
                        break;
                    case 'Home':
                        event.preventDefault();
                        focusItem(items, 0);
                        break;
                    case 'End':
                        event.preventDefault();
                        focusItem(items, items.length - 1);
                        break;
                    case 'Tab':
                        closeDropdown(dropdown);
                        break;
                    case 'Escape':
                        closeDropdown(dropdown);
                        trigger?.focus();
                        break;
                }
            };

            const initDropdown = (dropdown) => {
                const { trigger, content, items } = getDropdownElements(dropdown);

                if (!trigger || !content) {
                    return;
                }

                content.setAttribute('role', content.getAttribute('role') || 'menu');

                items.forEach((item) => {
                    item.setAttribute('role', item.getAttribute('role') || 'menuitem');
                    item.setAttribute('tabindex', item.getAttribute('tabindex') || '-1');
                    item.addEventListener('click', () => closeDropdown(dropdown));
                });

                trigger.addEventListener('click', (event) => {
                    event.preventDefault();
                    const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
                    if (isExpanded) {
                        closeDropdown(dropdown);
                    } else {
                        openDropdown(dropdown);
                    }
                });

                trigger.addEventListener('keydown', (event) => handleTriggerKeyDown(event, dropdown));
                content.addEventListener('keydown', (event) => handleContentKeyDown(event, dropdown));
                content.addEventListener('click', (event) => event.stopPropagation());
            };

            const init = () => {
                const dropdowns = document.querySelectorAll(DROPDOWN_SELECTOR);

                if (!dropdowns.length) {
                    return;
                }

                dropdowns.forEach(initDropdown);

                document.addEventListener('click', (event) => {
                    if (!event.target.closest(DROPDOWN_SELECTOR)) {
                        closeAllDropdowns();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        closeAllDropdowns();
                    }
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
</body>
</html>
