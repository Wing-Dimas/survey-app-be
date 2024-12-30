<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <title>Survey Sucess</title>

    @include('includes.meta')

    @include('includes.style')

</head>

<body>
    <div id="app" class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-6xl font-semibold text-gray-700 dark:text-gray-200">Thanks for your feedback</h1>
        </div>
    </div>
    @include('includes.script')
</body>

</html>
