<div class="p-6 bg-gray-100 min-h-screen">
    <!-- Flash Messages -->
@if (session()->has('message'))
<div
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 2000)"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
    <i class="fas fa-check-circle"></i> {{ session('message') }}
</div>
@endif
    <!-- Page Header and Navigation -->
<div class="mb-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold text-gray-700">
        <i class="fas fa-clinic-medical text-blue-500"></i> Medicine Management
    </h1>
    <div class="flex space-x-2">
        <!-- New Medicine or Back to List -->
        @if ($currentPage === 'list')
            <button wire:click="switchPage('form')"
                class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600 focus:outline-none">
                <i class="fas fa-plus"></i> New Medicine
            </button>
            <button wire:click="switchPage('stockList')"
                class="px-4 py-2 bg-green-500 text-white rounded-md shadow-sm hover:bg-green-600 focus:outline-none">
                Stocks Added
            </button>
        @elseif ($currentPage === 'form' || $currentPage === 'edit')
            <button
                wire:click="switchPage('list')"
                class="px-4 py-2 bg-gray-500 text-white rounded-md shadow-sm hover:bg-gray-600 focus:outline-none">
                <i class="fas fa-arrow-left"></i> Back to List
            </button>
        @endif

        <!-- Navigate to Sales -->
        <button
            wire:click="navigateToSales"
            class="px-4 py-2 bg-green-500 text-white rounded-md shadow-sm hover:bg-green-600 focus:outline-none"
        >
            <i class="fas fa-shopping-cart"></i> Sales
        </button>
    </div>
</div>

    <!-- Conditional Views -->
@if ($currentPage === 'list')
<!-- Medicine List Table -->
<div class="bg-white shadow-md rounded-md p-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">
        <i class="fas fa-pills text-green-500"></i> Medicine List
    </h3>
    <div class="mb-4 relative">
        <!-- Input Field -->
        <input type="text" id="search" wire:model="search"
            placeholder="Search by Name, Category, or Classification"
            class="rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 w-full pl-10">
        <!-- Search Icon Button -->
        <button type="button"
            class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500"
            wire:click="performSearch">
            <i class="fas fa-search"></i>
        </button>
    </div>


    <table class="min-w-full bg-white border">
        <thead>
            <tr class="bg-gray-100 font-serif font-bold border-b">
                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">ID</th>
                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">MedName</th>
                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Manufacturer/Company</th>
                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Quantity(No. of Tablets/Bottle)</th>
                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Price(Per Unit)</th>
                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Category</th>
                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Classification</th>
                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Expiry Date</th>
                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($medicines as $medicine)
                <tr wire:key="medicine-{{ $medicine->id }}" class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4 text-sm text-gray-700">{{ $medicine->medicine_id }}</td>
                    <td class="py-2 px-4 text-sm text-gray-700">{{ $medicine->name }}</td>
                    <td class="py-2 px-4 text-sm text-gray-700">{{ $medicine->company }}</td>
                    <td class="py-2 px-4 text-sm text-gray-700">{{ $medicine->quantity }}</td>
                    <td class="py-2 px-4 text-sm text-gray-700">{{ $medicine->price }}</td>
                    <td class="py-2 px-4 text-sm text-gray-700">
                        {{ $medicine->category ? $medicine->category->name : 'Uncategorized' }}
                    </td>
                    <td class="py-2 px-4 text-sm text-gray-700">{{ $medicine->classification ? $medicine->classification->name : 'Unclassified' }}</td>
                    <td class="py-2 px-4 text-sm text-gray-700">{{ $medicine->expiry_date->format('Y-m-d') }}</td>
                    <td class="py-2 px-4 text-sm">
                        <button wire:click="editMedicine({{ $medicine->id }})"
                            class="bg-white text-green-500 px-2 py-1 rounded-md hover:text-white hover:bg-blue-700">
                            Edit
                        </button>
                        <button wire:click="deleteMedicine({{ $medicine->id }})"
                            class="bg-white text-red-500 px-2 py-1 rounded-md hover:text-white hover:bg-red-800">
                            Delete
                        </button>
                        <button wire:click="switchToAddStock({{ $medicine->id }})"
                            class="bg-white text-green-700 px-2 font-serif py-1 rounded-md hover:text-white hover:bg-blue-600">
                            Add Stock
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@elseif ($currentPage === 'addStock')
<!-- Add Stock Form -->
<div class="bg-white shadow-md rounded-md p-6">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">
        <i class="fas fa-box-open text-yellow-500"></i> Add Stock for {{ $currentMedicineName }}
    </h2>
    <form wire:submit.prevent="addStockToDatabase">
        <div>
            <label for="newStock" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
            <input type="number" id="newStock" wire:model="newStock"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-yellow-500 focus:border-yellow-500 text-gray-700">
            @error('newStock')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mt-4 flex gap-2">
            <button type="submit"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md shadow-sm hover:bg-yellow-600 focus:outline-none">
                <i class="fas fa-plus-circle"></i> Add Stock
            </button>
            <button type="button" wire:click="cancelAddStock"
                class="px-4 py-2 bg-gray-500 text-white rounded-md shadow-sm hover:bg-gray-600 focus:outline-none">
                <i class="fas fa-times-circle"></i> Cancel
            </button>
        </div>
    </form>
</div>

@elseif ($currentPage === 'stockList')
        <!-- Stock List -->
        <div class="bg-white shadow-md rounded-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-box-open text-green-500 mr-2"></i> Stock Additions
            </h3>
            <!-- Date Filter -->
            <div class="flex flex-wrap gap-4 items-center mb-4">
                <select
                    wire:model="filterMonth"
                    class="block w-48 rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-700">
                    <option value="">All Months</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                    @endfor
                </select>
                <input
                    type="date"
                    wire:model="filterDate"
                    class="block w-48 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                <button
                    wire:click="resetFilter"
                    class="px-4 py-2 bg-gray-500 text-white rounded-md shadow-sm hover:bg-gray-600 focus:outline-none">
                    Reset Filter
                </button>
            </div>

            <!-- Stock Table -->
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="bg-gray-100 font-serif font-bold border-b">
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Stock ID</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Medicine Name</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Quantity Added</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stocks as $stock)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 px-4 text-sm text-gray-700">{{ $stock->id }}</td>
                            <td class="py-2 px-4 text-sm text-gray-700">{{ $stock->medicine->name }}</td>
                            <td class="py-2 px-4 text-sm text-gray-700">{{ $stock->quantity }}</td>
                            <td class="py-2 px-4 text-sm text-gray-700">{{ $stock->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">No stock additions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
@elseif ($currentPage === 'form' || $currentPage === 'edit')
<!-- Add/Edit Medicine Form -->
<div class="bg-white shadow-md rounded-md p-6">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">
        <i class="fas fa-prescription-bottle-alt text-blue-500"></i>
        {{ $editMode ? 'Edit Medicine' : 'Add New Medicine' }}
    </h2>
    <form wire:submit.prevent="{{ $editMode ? 'updateMedicine' : 'saveMedicine' }}">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <!-- Fields for Medicine Form -->
            <div>
                <label for="medicine_id" class="block text-sm font-medium text-gray-700">Medicine ID</label>
                <input type="text" id="medicine_id" wire:model="medicine_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 {{ $editMode ? 'bg-gray-200 cursor-not-allowed' : '' }}"
                    {{ $editMode ? 'disabled' : '' }}>
                @error('medicine_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Medicine Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">MedName</label>
                <input type="text" id="name" wire:model="name"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <!-- Company -->
            <div>
                <label for="company" class="block text-sm font-medium text-gray-700">Company/Manufacturer</label>
                <input type="text" id="company" wire:model="company"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('company') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <!-- Quantity -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity(No. of Tablets/Bottles)</label>
                <input type="number" id="quantity" wire:model="quantity"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 {{ $editMode ? 'bg-gray-200 cursor-not-allowed' : '' }}"
                {{ $editMode ? 'disabled' : '' }}
            >
            @error('medicine_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            <!-- Price -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Price(Per Tablet/Bottle)</label>
                <input type="text" id="price" wire:model="price"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select wire:model="category_id" id="category_id" class="block w-full mt-1">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="classification_id" class="block text-sm font-medium text-gray-700">Classification</label>
                <select wire:model="classification_id" id="classification_id" class="block w-full mt-1">
                    <option value="">Select Classification</option>
                    @foreach ($classifications as $classification)
                        <option value="{{ $classification->id }}">{{ $classification->name }}</option>
                    @endforeach
                </select>
                @error('classification_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                <input type="date" id="expiry_date" wire:model="expiry_date" class="block w-full mt-1">
                @error('expiry_date') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        <!-- Buttons -->
        <div class="mt-4 flex gap-2">
            <button type="submit"
                class="px-4 py-2 bg-green-300 text-black rounded-md shadow-sm hover:bg-green-600 focus:outline-none">
                <i class="fas fa-save"></i> {{ $editMode ? 'Update Medicine' : 'Add Medicine' }}
            </button>
            @if ($editMode)
                <button type="button" wire:click="cancelEdit"
                    class="px-4 py-2 bg-gray-500 text-white rounded-md shadow-sm hover:bg-gray-600 focus:outline-none">
                    <i class="fas fa-times-circle"></i> Cancel
                </button>
            @endif
        </div>
            </div>
            <!-- Include Modals -->
            @livewire('categories')
        </div>
    </form>
</div>
@endif
