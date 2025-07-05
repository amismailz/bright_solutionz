@php
    $imagePath = is_array($getState()) && count($getState()) ? $getState()[0] : null;
@endphp

@if ($imagePath)
    <img
        src="{{ route('download.secure.file', ['filename' => ltrim($imagePath, '/')]) }}"
        class="w-10 h-10 object-cover rounded-full"
        alt="Image"
    />
@else
    <span class="text-gray-400 text-sm">{{__('No Image')}}</span>
@endif
