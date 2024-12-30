<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <title>Survey</title>

    @include('includes.meta')

    @include('includes.style')

</head>

<body>
    <div id="app" class="min-h-screen py-24">
        <section class="max-w-lg mx-auto shadow-lg p-4 rounded-lg">
            <div class="flex justify-center">
                <img src="{{ asset('assets/img/logo/app-logo.png') }}" class="h-28" alt="app logo" />
            </div>
            <h1 class="text-xl text-center font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                Selamat Datang Di Survey Aplikasi <br> <span>{{ Str::upper($app_name) }}</span>
            </h1>
            <form action="{{ route('survey.store', $survey_token) }}" method="POST" class="w-full mx-auto mt-4">
                @csrf
                <div class="mb-5">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="user@gmail.com" required />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                @foreach ($formSubmissions as $field)
                    <div class="mb-5">
                        {{-- INPUT TYPE TEXT --}}
                        @if ($field->type == 'text')
                            <label for="{{ $field->field_name }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $field->question }}</label>
                            <input type="text" id="{{ $field->field_name }}" value="{{ old($field->field_name) }}" name="{{ $field->field_name }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required />
                        @endif

                        {{-- INPUT TYPE SELECT --}}
                        @if ($field->type == 'select')
                            <label for="{{ $field->field_name }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $field->question }}</label>
                            <select name="{{ $field->field_name }}" id="{{ $field->field_name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" @required($field->required)>
                                <option value="">Select an option</option>
                                @foreach ($field->options as $option)
                                    <option value="{{ $option->value }}" @checked(old($field->field_name) == $option->value)>{{ $option->value }}</option>
                                @endforeach
                            </select>
                        @endif

                        {{-- INPUT TYPE RADIO --}}
                        @if ($field->type == 'radio')
                            <label for="" class="block my-2 text-sm font-medium text-gray-900 dark:text-white">{{ $field->question }}</label>
                            @foreach ($field->options as $option)
                                <div class="flex items-center mb-4">
                                    <input id="{{ $field->field_name }}-{{ $option->key }}" type="radio" value="{{ $option->value }}" name="{{ $field->field_name }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="{{ $field->field_name }}-{{ $option->key }}" class="ms-2 text-sm font-regular text-gray-900 dark:text-gray-300">{{ $option->value }}</label>
                                </div>
                            @endforeach
                        @endif

                        {{-- INPUT TYPE NUMBER --}}
                        @if ($field->type == 'number')
                            <label for="{{ $field->field_name }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $field->question }}</label>
                            <input type="number" id="{{ $field->field_name }}" value="{{ old($field->field_name) }}" min="{{ $field->options[0]->min }}" max="{{ $field->options[0]->max }}" name="{{ $field->field_name }}" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="1" @required($field->required) />
                        @endif

                        {{-- INPUT TYPE TEXTAREA --}}
                        @if ($field->type == 'textarea')
                            <label for="{{ $field->field_name }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $field->question }}</label>
                            <textarea id="{{ $field->field_name }}" @required($field->required) name="{{ $field->field_name }}" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your thoughts here...">
                                {{ old($field->field_name) }}
                            </textarea>
                        @endif

                        {{-- ERROR --}}
                        @error($field->field_name)
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class=" flex justify-end">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Kirim Survey</button>
                </div>
            </form>
        </section>
    </div>

    @include('includes.script')
</body>

</html>
