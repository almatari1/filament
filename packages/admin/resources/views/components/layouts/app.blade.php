<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ trans('filament::layout.direction') === 'rtl' ? 'rtl' : 'ltr' }}" class="antialiased bg-gray-100 js-focus-visible">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? "{$title} - " : null }} {{ config('app.name') }}</title>

        <style>[x-cloak] { display: none !important; }</style>

        @livewireStyles

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&family=JetBrains+Mono&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="{{ route('filament.asset', [
            'id' => \Filament\get_asset_id('app.css'),
            'path' => 'app.css',
        ]) }}" />

        @foreach (\Filament\Facades\Filament::getStyles() as $path)
            <link rel="stylesheet" href="{{ $path }}" />
        @endforeach
    </head>

    <body>
        <div class="min-h-screen bg-gray-100 text-gray-800 font-sans antialiased grid grid-cols-[20rem,1fr]">
            <aside
                x-data="{}"
                x-bind:class="{ '-translate-x-full': ! $store.sidebar.isOpen, 'translate-x-0': $store.sidebar.isOpen }"
                class="fixed inset-y-0 left-0 z-20 w-80 h-screen bg-white shadow-xl rounded-r-2xl grid grid-rows-[4rem,1fr,auto] transition duration-500 transform lg:translate-x-0 -translate-x-full"
            >
                <header class="border-b px-6 flex items-center">
                    <a class="text-xl font-bold tracking-tight" href="{{ \Filament\Pages\Dashboard::geturl() }}">
                        {{ config('app.name') }}
                    </a>
                </header>

                <nav class="py-6">
                    <ul class="space-y-6 px-6">
                        @foreach (\Filament\Facades\Filament::getNavigation() as $group => $items)
                            <li>
                                @if ($group)
                                    <p class="font-bold uppercase text-gray-600 text-xs tracking-wider">
                                        {{ $group }}
                                    </p>
                                @endif

                                <ul @class([
                                    'text-sm space-y-1 -mx-3',
                                    'mt-2' => $group,
                                ])>
                                    @foreach ($items as $item)
                                        @php
                                            $isActive = $item->isActive();
                                        @endphp
                                        <li>
                                            <a
                                                href="{{ $item->getUrl() }}"
                                                @class([
                                                    'flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition',
                                                    'hover:bg-gray-500/5 focus:bg-gray-500/5' => ! $isActive,
                                                    'bg-primary-500 text-white' => $isActive,
                                                ])
                                            >
                                                <x-dynamic-component :component="$item->getIcon()" class="h-5 w-5" />

                                                <span>
                                                    {{ $item->getLabel() }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>

                            @if (! $loop->last)
                                <li>
                                    <div class="border-t -mr-6"></div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </nav>

                <footer class="border-t px-6 py-3 flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-gray-200"></div>

                    <div>
                        <p class="text-sm font-bold">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-gray-500 transition hover:text-gray-700 focus:text-gray-700">
                            <a href="{{ route('filament.logout') }}">
                                Sign out
                            </a>
                        </p>
                    </div>
                </footer>
            </aside>

            <div
                x-data="{}"
                x-cloak
                x-show="$store.sidebar.isOpen"
                x-transition.opacity.500ms
                x-on:click="$store.sidebar.close()"
                class="fixed inset-0 z-10 bg-gray-900/50 lg:hidden"
            ></div>

            <main
                x-data="{}"
                x-bind:class="{ 'translate-x-40 lg:translate-x-0': $store.sidebar.isOpen }"
                class="w-screen transform transition duration-500 relative lg:pl-80 lg:transition-none"
            >
                <header class="h-[4rem] border-b flex items-center">
                    <div class="flex items-center w-full max-w-6xl px-2 mx-auto sm:px-4 md:px-6 lg:px-8">
                        <button x-on:click="$store.sidebar.open()" class="flex-shrink-0 flex items-center justify-center w-10 h-10 text-primary-500 transition rounded-full hover:bg-gray-500/5 focus:bg-primary-500/10 focus:outline-none lg:hidden">
                            <x-heroicon-o-menu class="w-6 h-6" />
                        </button>

                        <div class="flex-1 flex items-center justify-between">
                            <div>
                                <ul class="hidden gap-4 items-center font-medium text-sm lg:flex">
                                    @foreach ($breadcrumbs as $url => $label)
                                        <li>
                                            <a
                                                href="{{ is_int($url) ? '#' : $url }}"
                                                @class([
                                                    'text-gray-500' => $loop->last,
                                                ])
                                            >
                                                {{ $label }}
                                            </a>
                                        </li>

                                        @if (! $loop->last)
                                            <li class="h-6 border-r border-gray-300 -skew-x-12"></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>

                            @livewire('filament.global-search')
                        </div>
                    </div>
                </header>

                <div class="w-full max-w-6xl px-4 py-6 mx-auto sm:px-4 md:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @livewireScripts

        <script>
            window.filamentData = @json(\Filament\Facades\Filament::getScriptData());
        </script>

        <script src="{{ route('filament.asset', [
            'id' => Filament\get_asset_id('app.js'),
            'path' => 'app.js',
        ]) }}"></script>

        @foreach (\Filament\Facades\Filament::getScripts() as $path)
            <script src="{{ $path }}"></script>
        @endforeach
    </body>
</html>