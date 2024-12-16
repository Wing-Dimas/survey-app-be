@extends('layouts.main')

@section('title', 'Form Submission')

@section('content')
    <section>
        <div class="mb-12">
            <h1 class="text-2xl text-gray-600 font-semibold">Form Submission</h1>
            @include('partials.breadcrumb', ['breadcrumbs' => ['Dashboard', 'Form Submission', 'Update Field']])
        </div>

        {{-- Show error --}}
        @if ($errors->any())
            @foreach ($errors->all() as $key => $error)
                <div id="toast-danger-{{ $key }}" class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                        </svg>
                        <span class="sr-only">Error icon</span>
                    </div>
                    <div class="ms-3 text-sm font-normal">{{ $error }}</div>
                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-danger-{{ $key }}" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
            @endforeach
        @endif

        <div class="space-y-4 md:space-y-6">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                Update Field
            </h1>
            <form action="{{ route('form-submission.update', $formSubmission->id) }}" method="POST" class="space-y-4 md:space-y-6" >
                @csrf
                @method('PUT')
                <div class="flex flex-wrap gap-8">
                    <div class="flex-1 min-w-64">
                        <label for="fake-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Field</label>
                        <input type="text" name="fake-name" id="fake-name" value="{{ old('fake-name', $formSubmission->field_name) }}" class="bg-gray-50 border border-gray-300 @error('name') border-red-500  @enderror text-gray-900 rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan nama field">
                        <input type="hidden" name="field_name" id="name" value="{{ old('field_name', $formSubmission->field_name) }}">
                        <div class="show-true-name ms-3 hidden items-center gap-2 mt-1">
                            <svg class="w-4 h-4 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex flex-col text-xs">
                                <span class="true-name font-medium text-green-500">Your new field will be created as </span>
                                <span>The field name can only contain ASCII letters, digits, and the characters ., -, and _.</span>
                            </div>
                        </div>
                        @error('field_name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex-1 min-w-64">
                        <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Field Type</label>
                        <select id="type" name="type" class="bg-gray-50 border border-gray-300 @error('type') border-red-500  @enderror text-gray-900 rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected>Choose a Field Type</option>
                            @foreach (['text', 'email', 'number', 'textarea', 'checkbox', 'select', 'radio'] as $item)
                                <option  value="{{ $item }}" @selected(old('type', $formSubmission->type) == $item) >{{ Str::ucfirst($item) }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex-1 min-w-64">
                        <label for="question" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pertanyaan</label>
                        <input type="question" name="question" id="question" value="{{ old('question', $formSubmission->question) }}" class="bg-gray-50 border border-gray-300 @error('name') border-red-500  @enderror text-gray-900 rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan pertanyaan">
                        @error('question')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- CHECK IF IS CHECKBOX, RADIO, OR SELECT --}}
                <div id="key-value-wrapper" class="mt-8 hidden flex-col gap-3">
                    <h2 class="text-lg font-bold leading-tight tracking-tight text-gray-900 md:text-xl dark:text-white">Set key dan value</h2>
                    <div class="flex gap-4 mt-4">
                        <span class="block w-full mb-2 text-sm font-medium text-gray-900 dark:text-white">Key</span>
                        <span class="block w-full mb-2 text-sm font-medium text-gray-900 dark:text-white">Value</span>
                        <span class="block flex-shrink-0 w-7 h-7"></span>
                    </div>
                    <div class="flex items-center flex-nowrap gap-4">
                        <input type="text" name="key[]" id="" value="" class="key flex-1 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan key">
                        <input type="text" name="value[]" id="" value="" class="value flex-1 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan value">
                        <div class="flex-shrink-0">
                            <button id="add-key-value" type="button" class="p-3 text-sm font-medium text-gray-500 focus:outline-none bg-gray-100 rounded-lg hover:bg-gray-200 hover:text-gray-900 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white pointer-events-none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                                </svg>
                                <span class="sr-only">Add Key & Value</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CHECK IF IS NUMBER --}}
                <div id="min-max-wrapper" class="mt-8 hidden flex-col gap-3">
                    <h2 class="text-lg font-bold leading-tight tracking-tight text-gray-900 md:text-xl dark:text-white">Set Min Max</h2>
                    <div class="flex items-center flex-nowrap gap-4">
                        <div class="flex-1">
                            <label for="min" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Min</label>
                            <input type="number" name="min" id="min" value="{{ old('min', $formSubmission->type == 'number' ? $formSubmission->options[0]->min : null) }}" class="key flex-1 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nilai minimal">
                        </div>
                        <div class="flex-1">
                            <label for="max" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Max</label>
                            <input type="number" name="max" id="max" value="{{ old('max', $formSubmission->type == 'number' ? $formSubmission->options[0]->max : null) }}" class="key flex-1 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nilai maksimal">
                        </div>
                    </div>
                </div>

                <div class="">
                    <label class="inline-flex items-center cursor-pointer">
                        <input name="required" type="checkbox" value="1" class="sr-only peer" {{  old('required', $formSubmission->required) == '1'  ? 'checked' : '' }}>
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Required</span>
                    </label>
                </div>

                <button type="submit" class=" text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-sky-600 dark:hover:bg-sky-700 dark:focus:ring-sky-800">Update Field</button>
            </form>
        </div>

    </section>
@endsection

{{-- CREATE SLUG FOR NAME --}}
@push('addon-script')
    <script>
        const fakeName = document.querySelector('#fake-name');
        const trueName = document.querySelector('.true-name');
        const fieldName = document.querySelector('#name');
        const showTrueName = document.querySelector('.show-true-name');

        fakeName.addEventListener('input', () => {
            if(fakeName.value === '') {
                showTrueName.classList.add('hidden');
                showTrueName.classList.remove('flex');
                return;
            }
            showTrueName.classList.remove('hidden');
            showTrueName.classList.add('flex');
            let name = fakeName.value.toLowerCase();
            // create slug for name
            name = name.replace(/[^\w-]+/g, ' ');
            name = name.replace(/ /g, '-');
            fieldName.value = name;
            trueName.innerText = `Your new field will be created as ${name}`;
        });
    </script>
@endpush

{{-- OPTIONS --}}
@push('addon-script')
    <script>
        window.onload = () => {
            // Select elements for type and input wrappers
            const type = document.querySelector('#type');
            const keyInputElement = document.querySelector('#key-value-wrapper');
            const minMaxElement = document.querySelector('#min-max-wrapper');

            // Retrieve the old type value and set it or default to 'text'
            const typeRequest = "{!! old('type', $formSubmission->type) !!}";
            type.value = typeRequest || 'text';

            /**
             * Update the visibility of input elements based on the type selected
             *
             * @param {HTMLElement} type - The select element containing the type value
             */
            function updateInputVisibility(type) {
                if (['checkbox', 'radio', 'select'].includes(type.value)) {
                    keyInputElement.classList.remove('hidden');
                    keyInputElement.classList.add('flex');

                    minMaxElement.classList.remove('flex');
                    minMaxElement.classList.add('hidden');
                } else if (type.value === 'number') {
                    keyInputElement.classList.remove('flex');
                    keyInputElement.classList.add('hidden');

                    minMaxElement.classList.remove('hidden');
                    minMaxElement.classList.add('flex');
                } else {
                    keyInputElement.classList.remove('flex');
                    keyInputElement.classList.add('hidden');

                    minMaxElement.classList.remove('flex');
                    minMaxElement.classList.add('hidden');
                }
            }

            // Add event listener to update input visibility on type change
            type.addEventListener('change', () => updateInputVisibility(type));

            // Initialize input visibility based on the current type value
            updateInputVisibility(type);
        }

    </script>
@endpush

{{-- CHECK IF IS CHECKBOX, RADIO, OR SELECT --}}
@push('addon-script')
    <script>
        @php
            $oldType = old('type', $formSubmission->type);

            $oldKey = $oldValue = null;

            if(in_array($oldType, ['checkbox', 'radio', 'select'])) {
                $oldKey = old('key', $formSubmission->options->pluck('key')->toArray());
                $oldValue = old('value', $formSubmission->options->pluck('value')->toArray());
            }
        @endphp

        // Select key and value input elements
        const keyInput = document.querySelector('.key');
        const valueInput = document.querySelector('.value');

        // Select the wrapper for key-value inputs
        const keyInputWrapper = document.querySelector('#key-value-wrapper');

        // Retrieve old key and value data from the server side

        const oldKey   = @json($oldKey);
        const oldValue = @json($oldValue);

        /**
         * Creates a key and value input element
         *
         * @param {string} key The key of the input element
         * @param {string} value The value of the input element
         * @returns {string} The HTML string of the key and value input element
         */
        function createKeyValueInput(key, value) {
            const keyInputValue = `
                <input type="text" value="${key ?? ''}" name="key[]" id="" value="" class="key flex-1 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan key">
                <input type="text" value="${value ?? ''}" name="value[]" id="" value="" class="value flex-1 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan value">
            ` ;

            return keyInputValue;
        }

        /**
         * Called when the page is initialized.
         *
         * If the old key and value exists, it will generate the key and value input
         * and append it to the key-value wrapper.
         */
        function onInit() {
            // Check if the old key and value exists
            if (!!oldKey && !!oldValue) {
                // Clear the key-value wrapper
                keyInputWrapper.innerHTML = '';

                // Loop through the old key and value
                for (let i = 0; i < oldKey.length; i++) {
                    // Generate the key-value input
                    const keyValInput = createKeyValueInput(oldKey[i], oldValue[i]);

                    // Create a wrapper for the key-value input
                    const wrapper = document.createElement('div');
                    wrapper.classList.add('flex', 'items-center', 'flex-nowrap', 'gap-4');

                    // Set the innerHTML of the wrapper to the key-value input
                    wrapper.innerHTML = keyValInput;

                    // Check if it is the last iteration
                    if (i === oldKey.length - 1) {
                        // Generate the add button
                        const newBtnAdd = createAddButton();

                        // Append the add button to the wrapper
                        wrapper.appendChild(newBtnAdd);
                    } else {
                        // Generate the remove button
                        const removeBtn = createRemoveButton();

                        // Append the remove button to the wrapper
                        wrapper.appendChild(removeBtn);
                    }

                    // Append the wrapper to the key-value wrapper
                    keyInputWrapper.appendChild(wrapper);
                }
            }
        }

        /**
         * Creates a remove button for the key and value wrapper.
         *
         * @returns {HTMLDivElement} The remove button element.
         */
        function createAddButton() {
            const newBtnAdd = document.createElement('div');
                newBtnAdd.classList.add('flex-shrink-0');
                newBtnAdd.innerHTML = `<button id="add-key-value" type="button" class="p-3 text-sm font-medium text-gray-500 focus:outline-none bg-gray-100 rounded-lg hover:bg-gray-200 hover:text-gray-900 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white pointer-events-none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                                            </svg>
                                            <span class="sr-only">Add Key & Value</span>
                                        </button>`;

            return newBtnAdd;
        }

        /**
         * Creates a remove button for the key and value wrapper.
         *
         * @returns {HTMLDivElement} The remove button element.
         */
        function createRemoveButton() {
            const removeBtn = document.createElement('div');
                removeBtn.classList.add('flex-shrink-0');
                removeBtn.innerHTML = `<button id="remove-key-value" type="button" class="p-3 text-sm font-medium text-gray-500 focus:outline-none bg-gray-100 rounded-lg hover:bg-gray-200 hover:text-gray-900 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white pointer-events-none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/>
                                            </svg>
                                            <span class="sr-only">Remove Key & Value</span>
                                        </button>`;

            return removeBtn;
        }

        /**
         * Creates a wrapper for the key and value input.
         *
         * @returns {HTMLDivElement} The wrapper element.
         */
        function createKeyValueWrapper() {
            const keyInputClone = keyInput.cloneNode(true);
            const valueInputClone = valueInput.cloneNode(true);

            const wrapper = document.createElement('div');
            wrapper.classList.add('flex', 'items-center', 'flex-nowrap', 'gap-4');

            wrapper.appendChild(keyInputClone);
            wrapper.appendChild(valueInputClone);
            keyInputWrapper.appendChild(wrapper);

            const newBtnAdd = createAddButton();
            wrapper.appendChild(newBtnAdd);

            return wrapper;
        }

        window.addEventListener('click', (e) => {
            const target = e.target;
            // IF ADD KEY & VALUE
            if(target.id === 'add-key-value') {
                const removeBtn = createRemoveButton();
                // remove button from the wrapper
                target.parentElement.parentElement.appendChild(removeBtn);
                target.parentElement.remove();

                const wrapper = createKeyValueWrapper();

                keyInputWrapper.appendChild(wrapper);
            }else if(target.id === 'remove-key-value') {
                // remove elmenet parent from the wrapper
                target.parentElement.parentElement.remove();
            }
        });

        // Run the onInit function on page load
        // This function will initialize the key-value inputs
        onInit();
    </script>
@endpush

{{-- CHECK INPUT KEY --}}
@push('addon-script')
    <script>
        window.addEventListener('input', (e) => {
            const target = e.target;
            if(target.classList.contains('key')) {
                let val = target.value.toLowerCase();
                // remove over dash
                val = val.replace(/[^\w-]+/g, ' ').replace(/ /g, '-');
                val = val.replace(/-{2,}/g, '-');
                target.value = val;
            }
        });
    </script>
@endpush

{{-- CHECHK MIN MAX VALUE --}}
@push('addon-script')
    <script>
        window.addEventListener('change', (e) => {
            const target = e.target;
            if(target.id === 'min') {
                const max = document.querySelector('#max');
                if(parseInt(target.value) > parseInt(max.value)) {
                    max.value = target.value;
                }
            }else if(target.id === 'max') {
                const min = document.querySelector('#min');
                if(target.value < min.value) {
                    min.value = target.value;
                }
            }
        });
    </script>
@endpush
