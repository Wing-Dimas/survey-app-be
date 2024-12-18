@extends('layouts.main')

@section('title', 'Api Key')

@section('content')
    <section>
        <div class="mb-12">
            <h1 class="text-2xl text-gray-600 font-semibold">Api Key</h1>
            @include('partials.breadcrumb', ['breadcrumbs' => ['Dashboard', 'Api Key', 'Edit Application']])
        </div>

        <div class="space-y-4 md:space-y-6">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                Edit Application
            </h1>
            <form action="{{ route('api-key.update', $apiKey->id) }}" method="POST" class="space-y-4 md:space-y-6" >
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Aplikasi</label>
                    <input type="name" name="name" id="name" value="{{ old('name', $apiKey->name) }}" value="{{ old('name') }}" class="bg-gray-50 border border-gray-300 @error('name') border-red-500  @enderror text-gray-900 rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan nama aplikasi">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="inline-flex items-center mb-5 cursor-pointer">
                        <input type="checkbox" name="active" value="1" class="sr-only peer" {{  old('active', $apiKey->active) == '1'  ? 'checked' : '' }} >
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Status</span>
                    </label>
                </div>
                <button type="submit" class="text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-sky-600 dark:hover:bg-sky-700 dark:focus:ring-sky-800">
                    Update Application
                </button>
            </form>
        </div>

    </section>
@endsection
