<div class="p-6 bg-gray-50 min-h-screen">

    <div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-2">
            @php
                $totals = $this->calculateSalesSummary();
            @endphp

            <!-- Cash Sale Card -->
            <div class="bg-green-100 shadow-lg rounded-lg p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-green-200">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-money-bill-alt text-green-500 text-3xl"></i>
                    <h4 class="font-semibold text-lg text-gray-800">Cash Sales</h4>
                </div>
                <p class="text-xl font-bold text-green-600">{{ number_format($totals['cash'], 2) }}</p>
            </div>

            <!-- Mpesa Sale Card -->
            <div class="bg-blue-100 shadow-lg rounded-lg p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-blue-200">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-mobile-alt text-blue-500 text-3xl"></i>
                    <h4 class="font-semibold text-lg text-gray-800">Mpesa Sales</h4>
                </div>
                <p class="text-xl font-bold text-blue-600">{{ number_format($totals['mpesa'], 2) }}</p>
            </div>

            <!-- Bank Transfer Sale Card -->
            <div class="bg-yellow-100 shadow-lg rounded-lg p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-yellow-200">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-university text-yellow-500 text-3xl"></i>
                    <h4 class="font-semibold text-lg text-gray-800">Bank Transfer Sales</h4>
                </div>
                <p class="text-xl font-bold text-yellow-600">{{ number_format($totals['bank_transfer'], 2) }}</p>
            </div>

            <!-- Debt Sale Card -->
            <div class="bg-red-100 shadow-lg rounded-lg p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-red-200">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-credit-card text-red-500 text-3xl"></i>
                    <h4 class="font-semibold text-lg text-gray-800">Debt Sales</h4>
                </div>
                <p class="text-xl font-bold text-red-600">{{ number_format($totals['debt'], 2) }}</p>
            </div>

            <!-- Total Discounts Card -->
            <div class="bg-purple-100 shadow-lg rounded-lg p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-purple-200">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-percent text-purple-500 text-3xl"></i>
                    <h4 class="font-semibold text-lg text-gray-800">Total Discounts</h4>
                </div>
                <p class="text-xl font-bold text-purple-600">{{ number_format($totals['total_discount'], 2) }}</p>
            </div>

            <!-- Overall Total Card -->
            <div class="bg-gray-100 shadow-lg rounded-lg p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-gray-200">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-chart-line text-gray-500 text-3xl"></i>
                    <h4 class="font-semibold text-lg text-gray-800">Overall Total Sales</h4>
                </div>
                <p class="text-xl font-bold text-gray-700">{{ number_format($totals['overall_total'], 2) }}</p>
            </div>
        </div>
    </div>

    <!--message-->
    <div>
        @if (session()->has('message'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-center space-x-4 mt-7 mb-6">
        <button
            wire:click="switchView('sell')"
            class="px-4 py-2 rounded-lg text-white {{ $view === 'sell' ? 'bg-blue-500' : 'bg-gray-300' }} hover:bg-blue-600 focus:outline-none"
        >
            Sell Medicine
        </button>
        <button
            wire:click="switchView('sales')"
            class="px-4 py-2 rounded-lg text-white {{ $view === 'sales' ? 'bg-blue-500' : 'bg-gray-300' }} hover:bg-blue-600 focus:outline-none"
        >
            View Sales
        </button>
        <button
    wire:click="switchView('debtors')"
    class="px-4 py-2 rounded-lg text-white {{ $view === 'debtors' ? 'bg-blue-500' : 'bg-gray-300' }} hover:bg-blue-600 focus:outline-none">
    View Debtors
</button>

    </div>

   <!-- Conditional Content Rendering -->
<div>
    @if ($view === 'sell')
        <!-- Sell Medicine Form -->
        <form wire:submit.prevent="sellMedicine" class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6">
                <i class="fas fa-clinic-medical text-blue-500"></i> Sell Medicine
            </h2>

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Select Medicine -->
                <div>
                    <label for="medicine" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-pills text-green-500"></i> Select Medicine
                    </label>
                    <select
                        id="medicine"
                        wire:model="selectedMedicineId"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500">
                        <option value="">Choose Medicine</option>
                        @foreach ($medicines as $medicine)
                            <option value="{{ $medicine->id }}">
                                {{ $medicine->name }} (Stock: {{ $medicine->quantity }})
                            </option>
                        @endforeach
                    </select>
                    @error('selectedMedicineId') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sort-numeric-up text-yellow-500"></i> Quantity
                    </label>
                    <input
                        type="number"
                        id="quantity"
                        wire:model="quantity"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500"
                        placeholder="Enter quantity">
                    @error('quantity') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Price Per Unit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-dollar-sign text-green-500"></i> Price Per Unit
                    </label>
                    <input
                        type="text"
                        value="{{ $price_per_unit }}"
                        class="w-full px-4 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg"
                        disabled>
                </div>

                <!-- Total Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-dollar-sign text-green-500"></i> Total Price
                    </label>
                    <input
                        type="text"
                        value="{{ $total_price }}"
                        class="w-full px-4 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg"
                        disabled>
                </div>

                <!-- Sold By -->
                <div>
                    <label for="sold_by" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user text-gray-500"></i> Sold By
                    </label>
                    @if (auth()->check())
                        <select
                            id="sold_by"
                            wire:model="soldBy"
                            class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500">
                            @foreach (\App\Models\User::all() as $user)
                                <option value="{{ $user->name }}" {{ auth()->user()->name === $user->name ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <script>
                            window.location.href = "{{ route('welcome') }}";
                        </script>
                    @endif
                    @error('soldBy') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave text-green-500"></i> Payment Method
                    </label>
                    <select
                        id="payment_method"
                        wire:model="paymentMethod"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500">
                        <option value="">Select Payment Method</option>
                        <option value="Mpesa">Mpesa</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cash">Cash</option>
                        <option value="Debt">Debt</option>
                    </select>
                    @error('paymentMethod') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Discount -->
                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tags text-red-500"></i> Discount (Ksh)
                    </label>
                    <input
                        type="number"
                        id="discount"
                        wire:model="discount"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500"
                        placeholder="Enter discount amount">
                    @error('discount') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Additional Fields for Debt -->
                @if ($paymentMethod === 'Debt')
                    <div>
                        <label for="debtor_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-gray-500"></i> Debtor Name
                        </label>
                        <input
                            type="text"
                            id="debtor_name"
                            wire:model="debtorName"
                            class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="Enter debtor's name">
                        @error('debtorName') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="debtor_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone text-gray-500"></i> Debtor Phone
                        </label>
                        <input
                            type="text"
                            id="debtor_phone"
                            wire:model="debtorPhone"
                            class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="Enter debtor's phone">
                        @error('debtorPhone') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-blue-500 text-white px-4 py-2 mt-6 rounded-lg hover:bg-blue-600 focus:outline-none">
                <i class="fas fa-cash-register"></i> Sell
            </button>
        </form>

    @elseif ($view === 'sales')
        <!-- Sales Listing -->
        <div class="bg-white p-6 rounded-lg shadow-md max-w-8xl mx-auto">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-list text-blue-500 mr-2"></i> Sales Listing
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-200 rounded-lg shadow-sm">
                    <thead class="bg-blue-100 text-gray-700">
                        <tr>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Medicine</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Quantity(No. of Tablets/Bottle)</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Price Per Unit/Tablet/Bottle</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Total Price</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Discount</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Payment Method</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Sold By</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr wire:key="sale-{{ $sale->id }}" class="hover:bg-gray-50">
                                <td class="border-b border-gray-300 px-4 py-2">{{ $sale->medicine->name ?? 'N/A' }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">{{ $sale->quantity_sold }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">Ksh. {{ $sale->price_per_unit }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">Ksh. {{ $sale->total_price }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">Ksh. {{ $sale->discount }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">{{ $sale->payment_method }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">{{ $sale->sold_by }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">
                                    <button
                                        wire:click="editSale({{ $sale->id }})"
                                        class="text-blue-500 hover:text-blue-700 px-2 py-1 rounded bg-blue-50 hover:bg-blue-100 transition">
                                        Edit
                                    </button>
                                </td>
                                <td class="border-b border-gray-300 px-4 py-2">
                                    <button
                                        wire:click="deleteSale({{ $sale->id }})"
                                        class="bg-white text-red-500 px-2 py-1 rounded-md hover:text-white hover:bg-red-800">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($sales->isEmpty())
                <p class="text-gray-500 mt-4 text-center">No sales have been recorded yet.</p>
            @endif
        </div>

    @elseif ($view === 'edit-sale')
        <!-- Edit Sales Form -->
        <form wire:submit.prevent="updateSale" class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">
                <i class="fas fa-edit text-blue-500"></i> Edit Sale
            </h2>

            <div class="mb-4">
                <label for="edit_medicine" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-pills text-green-500"></i> Select Medicine
                </label>
                <select
                    id="edit_medicine"
                    wire:model="selectedMedicineId"
                    class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500">
                    <option value="">Choose Medicine</option>
                    @foreach ($medicines as $medicine)
                        <option value="{{ $medicine->id }}">
                            {{ $medicine->name }} (Stock: {{ $medicine->quantity }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="edit_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sort-numeric-up text-yellow-500"></i> Quantity
                </label>
                <input
                    type="number"
                    id="edit_quantity"
                    wire:model="quantity"
                    class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500"
                    placeholder="Enter quantity">
            </div>

            <div class="mb-4">
                <label for="edit_sold_by" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user text-gray-500"></i> Sold By
                </label>
                <select
                    id="edit_sold_by"
                    wire:model="sold_by"
                    class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500">
                    @foreach (\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign text-green-500"></i> Total Price
                </label>
                <input
                    type="text"
                    value="{{ $total_price }}"
                    class="w-full px-4 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg"
                    disabled>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>

        @elseif ($view === 'debtors')
        <!-- Debtors View -->
        <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-user text-gray-500 mr-2"></i> Debtors
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-200 rounded-lg shadow-sm">
                    <thead class="bg-red-100 text-gray-700">
                        <tr>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Debtor Name</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Phone</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Medicine</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Amount Owed</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Pay Status</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($debtors as $debtor)
                            <tr wire:key="debtor-{{ $debtor->id }}" class="hover:bg-gray-50">
                                <td class="border-b border-gray-300 px-4 py-2">{{ $debtor->name }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">{{ $debtor->phone }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">{{ $debtor->medicine->name ?? 'N/A'}}</td>
                                <td class="border-b border-gray-300 px-4 py-2">
                                    <span class="font-bold {{ $debtor->status === 'Complete' ? 'text-green-500' : 'text-red-500' }}">
                                        Ksh. {{ $debtor->amount_owed }}
                                    </span>
                                </td>
                                <td class="border-b border-gray-300 px-4 py-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded
                                        {{ $debtor->status === 'Complete' ? 'bg-green-100 text-green-500' : 'bg-red-100 text-red-500' }}">
                                        {{ $debtor->status }}
                                    </span>
                                </td>
                                <td class="border-b border-gray-300 px-4 py-2">
                                    <button
                                        wire:click="toggleDebtorStatus({{ $debtor->id }})"
                                        class="px-4 py-2 rounded text-white transition
                                        {{ $debtor->status === 'Complete' ? 'bg-red-700 hover:bg-red-700' : 'bg-green-700 hover:bg-green-700' }}">
                                        {{ $debtor->status === 'Complete' ? 'Mark as Incomplete' : 'Mark as Paid' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($debtors->isEmpty())
                <p class="text-gray-500 mt-4 text-center">No debtors have been recorded yet.</p>
            @endif
        </div>
    @endif
</div>

