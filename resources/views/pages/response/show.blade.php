@extends('layouts.main')

@section('title', 'Detail Response')

@section('content')
    <section>
        <div class="mb-12">
            <h1 class="text-2xl text-gray-600 font-semibold">Detail</h1>
            @include('partials.breadcrumb', ['breadcrumbs' => ['Dashboard', 'Api Key', 'Detail']])
        </div>

        <section class="space-y-4 md:space-y-6">
            <div class="py-4 md:py-8">
              <div class="mb-4 grid gap-4 sm:grid-cols-2 sm:gap-8 lg:gap-16">
                <div class="space-y-4">
                    <dl class="">
                        <dt class="font-semibold text-gray-900 dark:text-white">Name</dt>
                        <dd class="text-gray-500 dark:text-gray-400">{{ $response->name }}</dd>
                    </dl>
                    <dl class="">
                        <dt class="font-semibold text-gray-900 dark:text-white">Email Address</dt>
                        <dd class="text-gray-500 dark:text-gray-400">{{ $response->email }}</dd>
                    </dl>
                </div>
                <div class="space-y-4">
                    <dl>
                        <dt class="font-semibold text-gray-900 dark:text-white">Nama Aplikasi</dt>
                        <dd class="text-gray-500 dark:text-gray-400">{{ $response->api_key->name }}</dd>
                    </dl>
                    <dl>
                        <dt class="font-semibold text-gray-900 dark:text-white">Di update pada</dt>
                        <dd class="text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($response->updated_at)->format('d M Y') }}</dd>
                    </dl>
                </div>
                </div>
            </div>


            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Pertanyaan</th>
                            <th scope="col" class="px-4 py-3">Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($response->answers as $answer)
                            <tr class="border-b dark:border-gray-700">
                                {{-- QUESTION --}}
                                <th scope="row" class="px-4 py-3 dark:text-white w-1/2 align-top">
                                    <span class="truncate whitespace-normal">
                                        {{ $answer->form_submission->question }}
                                    </span>
                                </th>
                                {{-- ANSWER --}}
                                <td class="px-4 py-3 w-1/2 text-start align-top">
                                    <span class="truncate whitespace-normal">
                                        {{ $answer->answer }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


        </section>

    </section>
@endsection
