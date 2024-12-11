<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <title>@yield('title') | Survey</title>

    @include('includes.meta')

    @include('includes.style')


    <!-- CSS Libraries -->
    @stack('addon-style')

</head>

<body>
    <div id="app" class="min-h-screen">
        @include('partials.navbar')
        @include('partials.sidebar')

        {{-- MAIN CONTENT --}}
        <div class="flex flex-col p-4 sm:ml-64 min-h-screen">
            <div class="p-4 mt-14">
                @yield('content')
            </div>

            @include('partials.footer')
        </div>
    </div>

    @include('includes.script')

    <!-- JS Libraies -->
    @stack('addon-script')

</body>

</html>
