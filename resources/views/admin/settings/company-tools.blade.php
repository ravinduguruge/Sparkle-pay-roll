@extends('layouts.master')

@section('title', 'Company Tools Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Company Tools Management</h1>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-md">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 mb-6 rounded-md">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Add New Tool Form -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-[#19264bff] px-6 py-4">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-plus-circle mr-2"></i>Add New Company Tool
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.company_tools.store') }}" method="POST">
                @csrf
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tool Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50" placeholder="e.g., Drill Machine, Hammer, etc.">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price (Rs.) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" step="0.01" min="0" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50" placeholder="0.00">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-[#19264bff] hover:bg-[#0f1729] text-white font-bold py-2 px-6 rounded transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>Add Tool
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tools List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-[#19264bff] px-6 py-4">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-toolbox mr-2"></i>All Company Tools
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#19264bff]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tool Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tools as $tool)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><i class="fas fa-wrench text-gray-500 mr-2"></i>{{ $tool->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600">Rs {{ number_format($tool->price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tool->is_active)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="editTool({{ $tool->id }}, '{{ $tool->name }}', {{ $tool->price }}, {{ $tool->is_active ? 'true' : 'false' }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                
                                @if($tool->is_active)
                                    <form action="{{ route('admin.company_tools.update', $tool->id) }}" method="POST" class="inline-block mr-3">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $tool->name }}">
                                        <input type="hidden" name="price" value="{{ $tool->price }}">
                                        <input type="hidden" name="is_active" value="0">
                                        <button type="submit" class="text-orange-600 hover:text-orange-900" onclick="return confirm('Deactivate this tool? It will no longer appear in the daily work form.');">
                                            <i class="fas fa-ban"></i> Deactivate
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.company_tools.update', $tool->id) }}" method="POST" class="inline-block mr-3">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $tool->name }}">
                                        <input type="hidden" name="price" value="{{ $tool->price }}">
                                        <input type="hidden" name="is_active" value="1">
                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-check-circle"></i> Activate
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.company_tools.destroy', $tool->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this tool? This will fail if the tool has been used in work expenses.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-toolbox text-4xl mb-3"></i>
                                <p>No company tools added yet.</p>
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
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Edit Company Tool</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tool Name</label>
                        <input type="text" id="edit_name" name="name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price (Rs.)</label>
                        <input type="number" id="edit_price" name="price" step="0.01" min="0" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50">
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="edit_is_active" name="is_active" class="rounded border-gray-300 text-[#19264bff] shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
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
function editTool(id, name, price, isActive) {
    document.getElementById('editForm').action = '/admin/company-tools/' + id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_is_active').checked = isActive;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection
