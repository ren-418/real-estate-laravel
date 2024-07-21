<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\PropertyFeature;
use Livewire\WithPagination;

class PropertyList extends Component
{
    use WithPagination;

    public $search = '';
    public $minPrice = 0;
    public $maxPrice = 1000000;
    public $minBedrooms = 0;
    public $maxBedrooms = 10;
    public $minBathrooms = 0;
    public $maxBathrooms = 10;
    public $minArea = 0;
    public $maxArea = 10000;
    public $propertyType = '';
    public $selectedAmenities = [];

    protected $listeners = ['applyAdvancedFilters'];

    public function applyAdvancedFilters($filters)
    {
        $this->search = $filters['search'];
        $this->minPrice = $filters['minPrice'];
        $this->maxPrice = $filters['maxPrice'];
        $this->minBedrooms = $filters['minBedrooms'];
        $this->maxBedrooms = $filters['maxBedrooms'];
        $this->minBathrooms = $filters['minBathrooms'];
        $this->maxBathrooms = $filters['maxBathrooms'];
        $this->minArea = $filters['minArea'];
        $this->maxArea = $filters['maxArea'];
        $this->propertyType = $filters['propertyType'];
        $this->selectedAmenities = $filters['selectedAmenities'];

        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getPropertiesProperty()
    {
        try {
            $query = Property::query();

            \Log::info('Initial query count: ' . $query->count());

            $query->search($this->search);
            \Log::info('After search filter count: ' . $query->count());

            $query->priceRange($this->minPrice, $this->maxPrice);
            \Log::info('After price range filter count: ' . $query->count());

            $query->bedrooms($this->minBedrooms, $this->maxBedrooms);
            \Log::info('After bedrooms filter count: ' . $query->count());

            $query->bathrooms($this->minBathrooms, $this->maxBathrooms);
            \Log::info('After bathrooms filter count: ' . $query->count());

            $query->areaRange($this->minArea, $this->maxArea);
            \Log::info('After area range filter count: ' . $query->count());

            if ($this->propertyType) {
                $query->propertyType($this->propertyType);
                \Log::info('After property type filter count: ' . $query->count());
            }

            if ($this->selectedAmenities) {
                $query->hasAmenities($this->selectedAmenities);
                \Log::info('After amenities filter count: ' . $query->count());
            }

            $properties = $query->with(['features', 'images'])->paginate(12);

            \Log::info('Final properties count: ' . $properties->count());
            \Log::info('Current page: ' . $properties->currentPage());
            \Log::info('Total pages: ' . $properties->lastPage());

            return $properties;
        } catch (\Exception $e) {
            \Log::error('Error fetching properties: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while fetching properties. Please try again.');
            return collect();
        }
    }
    
    public function render()
    {
        return view('livewire.property-list', [
            'properties' => $this->getPropertiesProperty(),
            'amenities' => PropertyFeature::distinct('feature_name')->pluck('feature_name'),
        ])->layout('layouts.app');
    }
}
