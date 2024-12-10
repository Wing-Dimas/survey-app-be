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
        <section class="background min-h-screen">
            <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0 relative z-50">
                <a href="#" class="flex items-center mb-6 text-3xl font-semibold text-white dark:text-white">
                    <img class="w-10 h-10 mr-2" src="{{ asset('assets/img/logo/app-logo.png') }}" alt="logo">
                    SurveyApp
                </a>
                <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700 ">
                    @yield('content')
                </div>
            </div>
        </section>
    </div>

    @include('includes.script')

    <!-- JS Libraies -->
    @stack('addon-script')

</body>

</html>
