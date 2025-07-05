<!-- Fancybox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

<!-- Images Section -->
<div>
    <h3 class="text-xl font-bold text-gray-800 mb-4">{{ __('Images') }}</h3>
    @if(!empty($record->image))
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($record->image as $img)
                @php
                    $url = route('download.secure.file', ['filename' => ltrim($img, '/')]);
                @endphp
                <a
                    href="{{ $url }}"
                    data-fancybox="gallery"
                    data-caption="Preview"
                    class="block"
                >
                    <img
                        src="{{ $url }}"
                        class="w-full h-32 object-cover rounded-lg border border-gray-200 shadow-sm hover:opacity-80 transition"
                        alt="Image"
                    />
                </a>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">{{ __('No images available.') }}</p>
    @endif
</div>


<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

<!-- Optional Fancybox Init -->
<script>
    Fancybox.bind('[data-fancybox="gallery"]', {
        // You can customize options here
        closeButton: "top",
    });
</script>
