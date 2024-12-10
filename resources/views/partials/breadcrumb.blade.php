<nav class="flex mt-2" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
    @foreach ($breadcrumbs as $index => $breadcrumb)
        <li
            @if ($index == 0) class="inline-flex items-center" @endif
            @if ($index == count($breadcrumbs) - 1) aria-current="page" @endif
        >
            <div class="flex items-center">
            @if ($index != 0)
                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
            @endif
            <span
                class="text-sm font-medium dark:text-gray-400 {{ $index == count($breadcrumbs) - 1 ? 'text-gray-700' : 'text-gray-500'}} @if ($index != 0)  md:ms-2 ms-1 @endif"
            >
                {{ $breadcrumb }}
            </span>
            </div>
        </li>
    @endforeach
    </ol>
</nav>
