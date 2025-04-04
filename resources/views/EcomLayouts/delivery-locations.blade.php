@extends('layouts.app')

@section('title', __('Delivery Locations'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Delivery Locations</h1>
</section>

<!-- Main content -->
<section class="content no-print">
    <div class="tw-bg-white tw-shadow-lg tw-rounded-lg tw-p-6 tw-mt-4">
        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="tw-mb-4 tw-p-3 tw-bg-red-100 tw-border tw-border-red-400 tw-text-red-700 tw-rounded">
                <ul class="tw-list-disc tw-pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Display error flash message -->
        @if(session('error'))
            <div class="tw-mb-4 tw-p-3 tw-bg-red-100 tw-border tw-border-red-400 tw-text-red-700 tw-rounded">
                {{ session('error') }}
            </div>
        @endif
        
        <!-- Display success flash message -->
        @if(session('success'))
            <div class="tw-mb-4 tw-p-3 tw-bg-green-100 tw-border tw-border-green-400 tw-text-green-700 tw-rounded">
                {{ session('success') }}
            </div>
        @endif
        
        <form action="{{ route('delivery-locations.store') }}" method="POST">
            @csrf
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                <!-- Business Name Dropdown -->
                <div>
                    <label class="tw-block tw-font-semibold tw-mb-2">Business Name</label>
                    <select name="business_location" class="tw-w-full tw-border tw-rounded-lg tw-p-2 tw-bg-white @error('business_location') tw-border-red-500 @enderror">
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}" {{ old('business_location') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                    @error('business_location')
                        <p class="tw-text-red-500 tw-text-sm tw-mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pincode Input -->
                <div>
                    <label class="tw-block tw-font-semibold tw-mb-2">Enter Pincode</label>
                    <input type="text" name="pincode" value="{{ old('pincode') }}" 
                           class="tw-w-full tw-border tw-rounded-lg tw-p-2 tw-bg-white @error('pincode') tw-border-red-500 @enderror" 
                           placeholder="Enter Pincode" required>
                    @error('pincode')
                        <p class="tw-text-red-500 tw-text-sm tw-mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="tw-mt-4">
                <button type="submit" class="tw-bg-blue-600 tw-text-white tw-px-4 tw-py-2 tw-rounded-lg">
                    Add Delivery Location
                </button>
            </div>
        </form>
    </div>

    <!-- Display Existing Delivery Locations -->
    <div class="tw-mt-6">
        <h2 class="tw-text-lg tw-font-bold">Existing Delivery Locations</h2>
        <table class="tw-w-full tw-border tw-mt-2">
            <thead class="tw-bg-gray-200">
                <tr>
                    <th class="tw-p-2">Business Name</th>
                    <th class="tw-p-2">Pincode</th>
                    <th class="tw-p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deliveryLocations as $delivery)
                    <tr class="tw-border-t">
                        <td class="tw-p-2">{{ $delivery->businessLocation->name }}</td>
                        <td class="tw-p-2">{{ $delivery->pincode }}</td>
                        <td class="tw-p-2">
                            <div class="tw-flex tw-space-x-2">
                                <!-- Edit Button -->
                                <button type="button" class=" btn btn-primary tw-bg-yellow-500 tw-text-white tw-px-3 tw-py-1 tw-rounded-lg tw-text-sm"
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $delivery->id }}">
                                    Edit
                                </button>
                                
                                <!-- Delete Button -->
                                <form action="{{ route('delivery-locations.destroy', $delivery->id) }}" method="POST" class="tw-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger tw-bg-red-500 tw-text-white tw-px-3 tw-py-1 tw-rounded-lg tw-text-sm"
                                            onclick="return confirm('Are you sure you want to delete this delivery location?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $delivery->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $delivery->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $delivery->id }}">Edit Delivery Location</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('delivery-locations.update', $delivery->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="edit_business_location_{{ $delivery->id }}" class="form-label">Business Name</label>
                                                    <select name="business_location" id="edit_business_location_{{ $delivery->id }}" class="form-select tw-bg-white">
                                                        @foreach ($locations as $location)
                                                            <option value="{{ $location->id }}" {{ $delivery->business_location_id == $location->id ? 'selected' : '' }}>
                                                                {{ $location->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_pincode_{{ $delivery->id }}" class="form-label">Pincode</label>
                                                    <input type="text" class="form-control" id="edit_pincode_{{ $delivery->id }}" name="pincode" value="{{ $delivery->pincode }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@stop

@section('javascript')
<script>
    $(document).ready(function () {
    $('.btn-primary').click(function () {
        let target = $(this).attr('data-bs-target');
        $(target).modal('show'); // Manually trigger modal
    });
});
</script>
@includeIf('sales_order.common_js')
@endsection

