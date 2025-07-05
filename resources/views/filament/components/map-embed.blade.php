<style>
    .map-container {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .map-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        filter: grayscale(30%) brightness(0.98) contrast(1);
        transition: filter 0.4s ease;
    }

    .map-container:hover iframe {
        filter: grayscale(0%) brightness(1) contrast(1.05);
    }

    .map-controls {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        z-index: 20;
    }

    .map-controls button {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(8px);
        border: none;
        border-radius: 9999px;
        padding: 0.5rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        transition: background 0.3s ease;
    }

    .map-controls button:hover {
        background: rgba(255, 255, 255, 1);
    }

    .map-loading {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        font-weight: 500;
        color: #4B5563;
    }

    @media (prefers-color-scheme: dark) {
        .map-container {
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.05);
        }
        .map-loading {
            background: rgba(17, 24, 39, 0.95);
            color: #D1D5DB;
        }
        .map-controls button {
            background: rgba(31, 41, 55, 0.85);
        }

        .map-controls button:hover {
            background: rgba(31, 41, 55, 1);
        }
    }
</style>

@php
if (isset($this->data))
    {
        $lat = $this->data['lat'];
        $long = $this->data['long'];
    }else
        {
            $lat = $this->cachedMountedTableActionRecord->lat;
            $long = $this->cachedMountedTableActionRecord->long;
        }
@endphp

@if($lat && $long)
    <div class="map-container">
        <div x-data="{ loading: true }">
            <div class="map-loading" x-show="loading">
                Loading map...
            </div>
            <iframe
                src="https://maps.google.com/maps?q={{ $lat }},{{ $long }}&z=15&output=embed"
                @load="loading = false"
                loading="lazy"
            ></iframe>

            <div class="map-controls">
                <button @click="window.open('https://www.google.com/maps/dir/?api=1&destination={{ $lat }},{{ $long }}', '_blank')" title="Directions">
                    <svg class="w-5 h-5 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                <button @click="window.open('https://www.google.com/maps?q={{ $lat }},{{ $long }}', '_blank')" title="View on Google Maps">
                    <svg class="w-5 h-5 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endif
