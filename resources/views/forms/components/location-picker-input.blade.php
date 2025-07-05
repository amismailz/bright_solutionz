<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div ax-load
        ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('location-picker-input') . '&time=' . time() }}"
        wire:ignore x-data="locationPickerInputScript({
            location: $wire.$entangle('{{ $getStatePath() }}'),
            config: {{ $getMapConfig() }},
        })" x-ignore>

        <div
            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5
                [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20
                [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500
                fi-fo-text-input overflow-hidden">

            <input type="text" name="location.picker.input.lat" id="location.picker.input.lat" placeholder="{{ __('Latitude') }}"
                class="fi-input block w-1/2 border-none py-1.5 text-base text-gray-950 transition duration-75
                placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)]
                disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500
                dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)]
                dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 bg-white/0 ps-3 pe-3"
                style="
                border: 2px solid #d1d5db;
                border-radius: 8px;
                padding: 8px 12px;
                transition: border-color 0.3s ease, box-shadow 0.3s ease;
                outline: none;
            "
                onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.2)';"
                onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';"
                onmouseover="this.style.borderColor='#a5b4fc';" onmouseout="this.style.borderColor='#d1d5db';" required>
            <input type="text" name="location.picker.input.lng" id="location.picker.input.lng" placeholder="{{ __('Longitude') }}"
                class="fi-input block w-1/2 border-none py-1.5 text-base text-gray-950 transition duration-75
                placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)]
                disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500
                dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)]
                dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 bg-white/0 ps-3 pe-3"
                style="
                border: 2px solid #d1d5db;
                border-radius: 8px;
                padding: 8px 12px;
                transition: border-color 0.3s ease, box-shadow 0.3s ease;
                outline: none;
            "
                onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.2)';"
                onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';"
                onmouseover="this.style.borderColor='#a5b4fc';" onmouseout="this.style.borderColor='#d1d5db';" required>

        </div>
        <div
            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5
             [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20
             [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500
             fi-fo-text-input overflow-hidden">
        </div>
        <div x-ref="map" class="locationPickr w-full" style="height: {{ $getHeight() }};"></div>
    </div>

    <script src="{{ asset('js/location-picker.js') }}"></script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ $getApiKey() }}&libraries=places,marker&loading=async">
    </script>

</x-dynamic-component>
