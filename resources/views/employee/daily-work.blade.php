@extends('layouts.master')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold text-slate-800 mb-6">Daily Work Entry</h1>

    {{-- Today's Work Section - Full Width --}}
    <div class="mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Submit Your Daily Work</h2>
            
            @if($user->can_manage_work || $user->role === 'admin')
                <form action="{{ route('employee.submit_daily_work') }}" method="POST" id="dailyWorkForm">
                    @csrf
                    
                    {{-- Work Details Section --}}
                    <div class="bg-slate-50 rounded-xl p-5 mb-4">
                        <h3 class="font-semibold text-slate-700 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Work Details
                        </h3>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Project *</label>
                                <select name="project_id" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Select Project --</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Work Date *</label>
                                <input type="date" name="work_date" value="{{ date('Y-m-d') }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-4 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Job Travel Start Time *</label>
                                <input type="datetime-local" name="travel_start_time" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Site On Time *</label>
                                <input type="datetime-local" name="site_on_time" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Site Out Time *</label>
                                <input type="datetime-local" name="site_out_time" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Job Travel End Time *</label>
                                <input type="datetime-local" name="travel_end_time" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Who Worked with You on Site? (Select Multiple)</label>
                            <select name="work_partners[]" multiple size="5" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple employees</p>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Job Description *</label>
                            <textarea name="description" rows="3" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Describe today's work..."></textarea>
                        </div>
                    </div>

                    {{-- Refreshments Section --}}
                    <div class="bg-slate-50 rounded-xl p-5 mb-4">
                        <h3 class="font-semibold text-slate-700 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Refreshments *
                        </h3>
                        
                        <div id="refreshments-container">
                            <div class="refreshment-item grid md:grid-cols-3 gap-3 mb-3">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Type *</label>
                                    <select name="refreshment_type[]" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                                        <option value="">-- Select Type --</option>
                                        <option value="breakfast">Breakfast</option>
                                        <option value="morning_tea">Morning Tea</option>
                                        <option value="lunch">Lunch</option>
                                        <option value="evening_tea">Evening Tea</option>
                                        <option value="dinner">Dinner</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Amount (Rs.) *</label>
                                    <input type="number" name="refreshment_amount[]" step="0.01" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addRefreshment()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">+ Add More Refreshment</button>
                    </div>

                    {{-- Vehicle & Transportation Section --}}
                    <div class="bg-slate-50 rounded-xl p-5 mb-4">
                        <h3 class="font-semibold text-slate-700 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Vehicle & Transportation *
                        </h3>
                        
                        <div id="vehicles-container">
                            <div class="vehicle-item grid md:grid-cols-4 gap-3 mb-3">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Vehicle *</label>
                                    <select name="vehicle_id[]" required class="vehicle-select w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" onchange="toggleDistanceField(this)">
                                        <option value="">-- Select Vehicle --</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" data-type="{{ $vehicle->type }}">
                                                {{ $vehicle->name }} ({{ ucfirst($vehicle->type) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="distance-field">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Distance (km) *</label>
                                    <input type="number" name="vehicle_distance[]" step="0.1" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" placeholder="0.0">
                                </div>
                                <div class="bus-amount-field" style="display: none;">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Ticket Amount (Rs.) *</label>
                                    <input type="number" name="bus_amount[]" step="0.01" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addVehicle()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">+ Add More Vehicle</button>
                    </div>

                    {{-- Other Expenses Section --}}
                    <div class="bg-slate-50 rounded-xl p-5 mb-4">
                        <h3 class="font-semibold text-slate-700 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Other Expenses
                        </h3>
                        
                        <div id="other-expenses-container">
                            <div class="other-expense-item grid md:grid-cols-3 gap-3 mb-3">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Item</label>
                                    <select name="other_expense_item_id[]" class="other-expense-select w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" onchange="toggleNewItemField(this)">
                                        <option value="">-- Select Item --</option>
                                        @foreach($otherExpenseItems as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                        <option value="new">+ Add New Item</option>
                                    </select>
                                    <input type="text" name="new_other_expense_item[]" placeholder="New item name" class="new-item-field w-full border border-slate-300 rounded-lg px-3 py-2 text-sm mt-2" style="display: none;">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Amount (Rs.)</label>
                                    <input type="number" name="other_expense_amount[]" step="0.01" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addOtherExpense()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">+ Add More Expense</button>
                    </div>

                    {{-- Purchasing Section (Only if user has permission) --}}
                    @if($user->can_add_purchases || $user->role === 'admin')
                    <div class="bg-slate-50 rounded-xl p-5 mb-4">
                        <h3 class="font-semibold text-slate-700 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Purchasing (Company Tools) <span class="text-xs text-slate-500 ml-2">(Optional)</span>
                        </h3>
                        
                        {{-- Available Tools Price List --}}
                        @if($companyTools->isNotEmpty())
                        <div class="mb-4 bg-white rounded-lg border border-slate-200 overflow-hidden">
                            <div class="bg-[#19264bff] px-4 py-2">
                                <p class="text-white text-sm font-medium">Available Items in Company Store</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-slate-100 border-b border-slate-200">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-slate-700 font-medium">Tool/Item Name</th>
                                            <th class="px-4 py-2 text-right text-slate-700 font-medium">Stock Available</th>
                                            <th class="px-4 py-2 text-right text-slate-700 font-medium">Price per Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($storeInventory as $item)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-4 py-2 text-slate-700">
                                                <svg class="w-4 h-4 inline mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ $item->companyTool->name }}
                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                <span class="font-semibold text-blue-700">{{ $item->available_quantity }} units</span>
                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                <span class="font-semibold text-green-700">Rs {{ number_format($item->avg_price, 2) }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-3 text-center text-slate-500 text-xs">
                                                No items available in store currently
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        
                        <div id="purchases-container">
                            <div class="purchase-item grid md:grid-cols-4 gap-3 mb-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Tool/Item from Store</label>
                                    <select name="company_tool_id[]" class="tool-select w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" onchange="updatePurchasePrice(this)">
                                        <option value="">-- Select from Store --</option>
                                        @foreach($storeInventory as $item)
                                            <option value="{{ $item->company_tool_id }}" 
                                                    data-price="{{ $item->avg_price }}" 
                                                    data-available="{{ $item->available_quantity }}">
                                                {{ $item->companyTool->name }} ({{ $item->available_quantity }} available)
                                            </option>
                                        @endforeach
                                        <option value="new">+ Add New Tool (Not in Store)</option>
                                    </select>
                                    <input type="text" name="new_company_tool[]" placeholder="New tool name" class="new-tool-field w-full border border-slate-300 rounded-lg px-3 py-2 text-sm mt-2" style="display: none;">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Quantity</label>
                                    <input type="number" name="purchase_quantity[]" min="1" step="1" class="purchase-quantity w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" placeholder="1" value="1" onchange="updatePurchaseTotal(this)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Unit Price (Rs.)</label>
                                    <input type="number" name="purchase_unit_price[]" step="0.01" class="purchase-unit-price w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" placeholder="0.00" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Total Amount (Rs.)</label>
                                    <input type="number" name="purchase_amount[]" step="0.01" class="purchase-total w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-slate-100" placeholder="0.00" readonly>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addPurchase()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">+ Add More Purchase</button>
                    </div>
                    @endif

                    {{-- Final Description --}}
                    <div class="bg-slate-50 rounded-xl p-5 mb-4">
                        <h3 class="font-semibold text-slate-700 mb-4">Summary/Additional Notes</h3>
                        <textarea name="summary_notes" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Any additional information about today's work..."></textarea>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-[#19264bff] text-white font-semibold rounded-lg hover:bg-[#0f1a3a] transition">
                            Submit Daily Work
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800 text-sm">
                        <strong>No Permission:</strong> You don't have permission to add daily work entries. Please contact your administrator.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function addRefreshment() {
    const container = document.getElementById('refreshments-container');
    const item = document.createElement('div');
    item.className = 'refreshment-item grid md:grid-cols-3 gap-3 mb-3';
    item.innerHTML = `
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-1">Type *</label>
            <select name="refreshment_type[]" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                <option value="">-- Select Type --</option>
                <option value="breakfast">Breakfast</option>
                <option value="morning_tea">Morning Tea</option>
                <option value="lunch">Lunch</option>
                <option value="evening_tea">Evening Tea</option>
                <option value="dinner">Dinner</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Amount (Rs.) *</label>
            <div class="flex gap-2">
                <input type="number" name="refreshment_amount[]" step="0.01" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" placeholder="0.00">
                <button type="button" onclick="this.closest('.refreshment-item').remove()" class="text-red-600 hover:text-red-800">×</button>
            </div>
        </div>
    `;
    container.appendChild(item);
}

function addVehicle() {
    const container = document.getElementById('vehicles-container');
    const item = document.createElement('div');
    item.className = 'vehicle-item grid md:grid-cols-4 gap-3 mb-3';
    item.innerHTML = `
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-1">Vehicle *</label>
            <select name="vehicle_id[]" required class="vehicle-select w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" onchange="toggleDistanceField(this)">
                <option value="">-- Select Vehicle --</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}" data-type="{{ $vehicle->type }}">
                        {{ $vehicle->name }} ({{ ucfirst($vehicle->type) }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="distance-field">
            <label class="block text-sm font-medium text-slate-700 mb-1">Distance (km) *</label>
            <input type="number" name="vehicle_distance[]" step="0.1" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" placeholder="0.0">
        </div>
        <div class="bus-amount-field" style="display: none;">
            <label class="block text-sm font-medium text-slate-700 mb-1">Ticket Amount (Rs.) *</label>
            <div class="flex gap-2">
                <input type="number" name="bus_amount[]" step="0.01" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" placeholder="0.00">
                <button type="button" onclick="this.closest('.vehicle-item').remove()" class="text-red-600 hover:text-red-800">×</button>
            </div>
        </div>
    `;
    container.appendChild(item);
}

function toggleDistanceField(select) {
    const parent = select.closest('.vehicle-item');
    const selectedOption = select.options[select.selectedIndex];
    const type = selectedOption.getAttribute('data-type');
    
    const distanceField = parent.querySelector('.distance-field');
    const busAmountField = parent.querySelector('.bus-amount-field');
    
    if (type === 'bus') {
        distanceField.style.display = 'none';
        busAmountField.style.display = 'block';
    } else if (type === 'company') {
        distanceField.style.display = 'block';
        busAmountField.style.display = 'none';
    } else {
        distanceField.style.display = 'none';
        busAmountField.style.display = 'none';
    }
}

function addOtherExpense() {
    const container = document.getElementById('other-expenses-container');
    const item = document.createElement('div');
    item.className = 'other-expense-item grid md:grid-cols-3 gap-3 mb-3';
    item.innerHTML = `
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-1">Item</label>
            <select name="other_expense_item_id[]" class="other-expense-select w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" onchange="toggleNewItemField(this)">
                <option value="">-- Select Item --</option>
                @foreach($otherExpenseItems as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
                <option value="new">+ Add New Item</option>
            </select>
            <input type="text" name="new_other_expense_item[]" placeholder="New item name" class="new-item-field w-full border border-slate-300 rounded-lg px-3 py-2 text-sm mt-2" style="display: none;">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Amount (Rs.)</label>
            <div class="flex gap-2">
                <input type="number" name="other_expense_amount[]" step="0.01" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" placeholder="0.00">
                <button type="button" onclick="this.closest('.other-expense-item').remove()" class="text-red-600 hover:text-red-800">×</button>
            </div>
        </div>
    `;
    container.appendChild(item);
}

function toggleNewItemField(select) {
    const parent = select.closest('.other-expense-item');
    const newItemField = parent.querySelector('.new-item-field');
    
    if (select.value === 'new') {
        newItemField.style.display = 'block';
    } else {
        newItemField.style.display = 'none';
    }
}

function addPurchase() {
    const container = document.getElementById('purchases-container');
    const item = document.createElement('div');
    item.className = 'purchase-item grid md:grid-cols-4 gap-3 mb-3';
    item.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Tool/Item from Store</label>
            <select name="company_tool_id[]" class="tool-select w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" onchange="updatePurchasePrice(this)">
                <option value="">-- Select from Store --</option>
                @foreach($storeInventory as $item)
                    <option value="{{ $item->company_tool_id }}" 
                            data-price="{{ $item->avg_price }}" 
                            data-available="{{ $item->available_quantity }}">
                        {{ $item->companyTool->name }} ({{ $item->available_quantity }} available)
                    </option>
                @endforeach
                <option value="new">+ Add New Tool (Not in Store)</option>
            </select>
            <input type="text" name="new_company_tool[]" placeholder="New tool name" class="new-tool-field w-full border border-slate-300 rounded-lg px-3 py-2 text-sm mt-2" style="display: none;">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Quantity</label>
            <input type="number" name="purchase_quantity[]" min="1" step="1" class="purchase-quantity w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" placeholder="1" value="1" onchange="updatePurchaseTotal(this)">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Unit Price (Rs.)</label>
            <input type="number" name="purchase_unit_price[]" step="0.01" class="purchase-unit-price w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" placeholder="0.00" readonly>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Total Amount (Rs.)</label>
            <div class="flex gap-2">
                <input type="number" name="purchase_amount[]" step="0.01" class="purchase-total w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-slate-100" placeholder="0.00" readonly>
                <button type="button" onclick="this.closest('.purchase-item').remove()" class="text-red-600 hover:text-red-800">×</button>
            </div>
        </div>
    `;
    container.appendChild(item);
}

function updatePurchasePrice(select) {
    const parent = select.closest('.purchase-item');
    const newToolField = parent.querySelector('.new-tool-field');
    const unitPriceInput = parent.querySelector('.purchase-unit-price');
    const quantityInput = parent.querySelector('.purchase-quantity');
    
    if (select.value === 'new') {
        newToolField.style.display = 'block';
        unitPriceInput.value = '';
        unitPriceInput.removeAttribute('readonly');
        updatePurchaseTotal(quantityInput);
    } else if (select.value === '') {
        newToolField.style.display = 'none';
        unitPriceInput.value = '';
        unitPriceInput.setAttribute('readonly', true);
        updatePurchaseTotal(quantityInput);
    } else {
        newToolField.style.display = 'none';
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        unitPriceInput.value = parseFloat(price).toFixed(2);
        unitPriceInput.setAttribute('readonly', true);
        updatePurchaseTotal(quantityInput);
    }
}

function updatePurchaseTotal(input) {
    const parent = input.closest('.purchase-item');
    const quantity = parseFloat(parent.querySelector('.purchase-quantity').value) || 0;
    const unitPrice = parseFloat(parent.querySelector('.purchase-unit-price').value) || 0;
    const totalInput = parent.querySelector('.purchase-total');
    
    const total = quantity * unitPrice;
    totalInput.value = total.toFixed(2);
}

function toggleNewToolField(select) {
    updatePurchasePrice(select);
}
</script>
@endsection
