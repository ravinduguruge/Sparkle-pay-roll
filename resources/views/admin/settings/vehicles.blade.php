@extends('layouts.master')

@section('title', 'Vehicle Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Vehicle Management</h1>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-md">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 mb-6 rounded-md">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add New Vehicle Form -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-[#19264bff] px-6 py-4">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-plus-circle mr-2"></i>Add New Vehicle
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.vehicles.store') }}" method="POST" id="vehicleForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50" placeholder="e.g., Company Van">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                        <select name="type" id="vehicleType" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50" onchange="toggleVehicleFields()">
                            <option value="">Select Type</option>
                            <option value="company">Company Vehicle (Calculate by KM)</option>
                            <option value="bus">Public Bus (Manual Entry)</option>
                        </select>
                    </div>

                    <div id="fuelRateField" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fuel Rate per KM (Rs.) <span class="text-red-500">*</span></label>
                        <input type="number" name="fuel_rate_per_km" step="0.01" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50" placeholder="0.00">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-[#19264bff] hover:bg-[#0f1729] text-white font-bold py-2 px-6 rounded transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Add Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Vehicles List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-[#19264bff] px-6 py-4">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-list mr-2"></i>All Vehicles
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#19264bff]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Vehicle Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Fuel Rate/KM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($vehicles as $vehicle)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $vehicle->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($vehicle->type === 'company')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-car mr-1"></i> Company
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-bus mr-1"></i> Bus (Manual Entry)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $vehicle->fuel_rate_per_km ? 'Rs ' . number_format($vehicle->fuel_rate_per_km, 2) . '/km' : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="editVehicle({{ $vehicle->id }}, '{{ $vehicle->name }}', '{{ $vehicle->type }}', {{ $vehicle->fuel_rate_per_km ?? 0 }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-car text-4xl mb-3"></i>
                                <p>No vehicles added yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Edit Vehicle</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Name</label>
                        <input type="text" id="edit_name" name="name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select id="edit_type" name="type" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50" onchange="toggleEditFields()">
                            <option value="company">Company Vehicle</option>
                            <option value="bus">Public Bus</option>
                        </select>
                    </div>
                    <div id="edit_fuelRateField">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fuel Rate per KM (Rs.)</label>
                        <input type="number" id="edit_fuel_rate_per_km" name="fuel_rate_per_km" step="0.01" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#19264bff] text-white rounded-md hover:bg-[#0f1729]">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleVehicleFields() {
    const type = document.getElementById('vehicleType').value;
    const fuelField = document.getElementById('fuelRateField');
    const busField = document.getElementById('busTicketField');
    
    if (type === 'company') {
        fuelField.style.display = 'block';
        busField.style.display = 'none';
        document.querySelector('[name="fuel_rate_per_km"]').required = true;
        document.querySelector('[name="bus_ticket_amount"]').required = false;
    } else if (type === 'bus') {
        fuelField.style.display = 'none';
        busField.style.display = 'block';
        document.querySelector('[name="fuel_rate_per_km"]').required = false;
        document.querySelector('[name="bus_ticket_amount"]').required = true;
    } else {
        fuelField.style.display = 'none';
        busField.style.display = 'none';
    }
}

function editVehicle(id, name, type, fuelRate, busTicket) {
    document.getElementById('editForm').action = '/admin/vehicles/' + id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_type').value = type;
    document.getElementById('edit_fuel_rate_per_km').value = fuelRate;
    document.getElementById('edit_bus_ticket_amount').value = busTicket;
    toggleEditFields();
    document.getElementById('editModal').classList.remove('hidden');
}

function toggleEditFields() {
    const type = document.getElementById('edit_type').value;
    const fuelField = document.getElementById('edit_fuelRateField');
    const busField = document.getElementById('edit_busTicketField');
    
    if (type === 'company') {
        fuelField.style.display = 'block';
        busField.style.display = 'none';
    } else {
        fuelField.style.display = 'none';
        busField.style.display = 'block';
    }
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection
