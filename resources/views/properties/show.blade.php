@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <img src="{{ $property->featured_image }}" alt="{{ $property->title }}" class="w-full h-auto rounded-lg shadow-lg">
        </div>
        <div>
            <h1 class="text-3xl font-bold mb-4">{{ $property->title }}</h1>
            <p class="text-2xl text-gray-700 mb-4">${{ number_format($property->price, 2) }}</p>
            
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Categories</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($property->categories as $category)
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>
            
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Features & Amenities</h2>
                <ul class="list-disc list-inside">
                    @foreach($property->features as $feature)
                        <li>{{ $feature->name }}</li>
                    @endforeach
                </ul>
            </div>
            
            <p class="text-gray-600 mb-4">{{ $property->description }}</p>
            
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Neighborhood</h2>
                <p>{{ $property->neighborhood_details }}</p>
            </div>
            
            @livewire('property-booking', ['propertyId' => $property->id])
            
            <div class="mt-8">
                <a href="{{ route('tenancy.apply', $property->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Apply for Tenancy
                </a>
            </div>
        </div>
    </div>
</div>
@endsection