@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-screen">
        <div class="car_spot flex-1">
            <div class="car_rotate relative select-none touch-pan-y">
                <div class="img_cont relative z-10 flex justify-center items-center">
                    <img class="mx-auto" height="150" src="{{ asset('images/mock_car_360/1.png') }}"
                        style="user-select: none; -webkit-user-drag: none; touch-action: pan-y;">
                    @for ($i = 2; $i <= 28; $i++)
                        <img class="mx-auto" height="150" src="{{ asset('images/mock_car_360/' . $i . '.png') }}"
                            style="display:none; z-index:10;user-select: none; -webkit-user-drag: none; touch-action: pan-y;">
                    @endfor
                </div>
                <div class="rotate_bg"></div>
            </div>
        </div>
    </div>

    <div class="mx-auto mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pt-6" style="border: solid 1px rgb(0, 0, 0, .5);">
        @php
            $cars = \App\Models\Car::all();
        @endphp

        @foreach ($cars as $car)
            <div class="card bg-white shadow-md rounded-lg overflow-hidden">
                <div class="card-image">
                    @if ($carImage = $car->getFirstMediaUrl('car_images_collection'))
                        <img src="{{ $carImage }}" alt="{{ $car->name }}" class="w-full h-48 object-cover">
                    @else
                        <img src="{{ asset('images/default_car.png') }}" alt="Default Image" class="w-full h-48 object-cover">
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold">{{ $car->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $car->description }}</p>
                    <div class="mt-4">
                        <a href="#" class="text-blue-500 hover:underline">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        const images = Array.from(document.querySelectorAll(".car_rotate img"));
        const imagesNum = images.length;
        const ape = 360 / imagesNum;

        const axes = new eg.Axes({
            angle: { range: [0, 360], circular: true }
        }, { deceleration: 0.01 });

        axes.on("change", ({ pos }) => {
            const index = Math.min(Math.round(pos.angle % 360 / ape), imagesNum - 1);
            images.forEach((v, i) => {
                v.style.display = i === index ? "inline-block" : "none";
            });
        });

        axes.connect("angle", new eg.Axes.PanInput(".car_rotate"));
    </script>
@endpush

@push('styles')
    <style>
        .card {
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-10px);
        }
    </style>
@endpush
