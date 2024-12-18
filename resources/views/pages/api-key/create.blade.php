@extends('layouts.main')

@section('title', 'Api Key')

@section('content')
    <section>
        <div class="mb-12">
            <h1 class="text-2xl text-gray-600 font-semibold">Api Key</h1>
            @include('partials.breadcrumb', ['breadcrumbs' => ['Dashboard', 'Api Key', 'Add Application']])
        </div>

        <div class="space-y-4 md:space-y-6">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                Add Application
            </h1>
            <form action="{{ route('api-key.store') }}" method="POST" class="space-y-4 md:space-y-6" >
                @csrf
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Aplikasi</label>
                    <input type="name" name="name" id="name" value="{{ old('name') }}" value="{{ old('name') }}" class="bg-gray-50 border border-gray-300 @error('name') border-red-500  @enderror text-gray-900 rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan nama aplikasi">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-sky-600 dark:hover:bg-sky-700 dark:focus:ring-sky-800">Add Application</button>
            </form>
        </div>

    </section>
@endsection
