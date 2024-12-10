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
    <div id="app">
        @include('partials.navbar')
        @include('partials.sidebar')

        {{-- MAIN CONTENT --}}
        <div class="p-4 sm:ml-64">
            <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
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
