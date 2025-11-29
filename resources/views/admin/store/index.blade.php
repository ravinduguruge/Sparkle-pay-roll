@extends('layouts.master')

@section('title', 'Company Store Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Company Store Inventory</h1>
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

    <!-- Current Inventory Summary -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-[#19264bff] px-6 py-4">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-warehouse mr-2"></i>Current Inventory Stock
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#19264bff]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Item Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Avg. Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total Value</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($inventory as $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <i class="fas fa-wrench text-gray-500 mr-2"></i>{{ $item->companyTool->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-blue-600">{{ $item->total_quantity }} units</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Rs {{ number_format($item->avg_price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600">Rs {{ number_format($item->total_quantity * $item->avg_price, 2) }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3"></i>
                                <p>No items in inventory yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add New Purchase Form -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-[#19264bff] px-6 py-4">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-plus-circle mr-2"></i>Add Purchase to Store
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.company_store.store') }}" method="POST">
                @csrf
                <div class="grid md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tool/Item <span class="text-red-500">*</span></label>
                        <select name="company_tool_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50">
                            <option value="">-- Select --</option>
                            @foreach($companyTools as $tool)
                                <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" min="1" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50" placeholder="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price (Rs.) <span class="text-red-500">*</span></label>
                        <input type="number" name="unit_price" step="0.01" min="0" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Date <span class="text-red-500">*</span></label>
                        <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-[#19264bff] hover:bg-[#0f1729] text-white font-bold py-2 px-6 rounded transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>Add
                        </button>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <input type="text" name="notes" maxlength="500" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50" placeholder="Any additional notes...">
                </div>
            </form>
        </div>
    </div>

    <!-- Purchase History -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-[#19264bff] px-6 py-4">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-history mr-2"></i>Purchase History
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#19264bff]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Purchased By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($storeItems as $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->purchase_date->format('Y-m-d') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <i class="fas fa-wrench text-gray-500 mr-2"></i>{{ $item->companyTool->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-blue-600">{{ $item->quantity }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Rs {{ number_format($item->unit_price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600">Rs {{ number_format($item->quantity * $item->unit_price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $item->purchasedBy->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">{{ $item->notes ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('admin.company_store.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this entry?');">
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
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                                <p>No purchase history yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
