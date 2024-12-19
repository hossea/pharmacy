<div class="p-6 bg-gray-50 min-h-screen">

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
    <div class="flex justify-center space-x-4 mb-6">
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
    </div>

   <!-- Conditional Content Rendering -->
<div>
    @if ($view === 'sell')
        <!-- Sell Medicine Form -->
        <form wire:submit.prevent="sellMedicine" class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">
                <i class="fas fa-clinic-medical text-blue-500"></i> Sell Medicine
            </h2>

            <!-- Select Medicine -->
            <div class="mb-4">
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
            <div class="mb-4">
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
            <div class="mb-4">
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

            <!-- Sold By -->
            <div class="mb-4">
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
                @error('sold_by') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Payment Method -->
            <div class="mb-4">
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-money-bill-wave text-green-500"></i> Payment Method
                </label>
                <select
    id="payment_method"
    wire:model="paymentMethod"
    class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500">
    <option value="">Select Payment Method</option>
    <option value="Mpesa">Mpesa</option>
    <option value="Cash">Cash</option>
    <option value="Debt">Debt</option>

</select>
@error('paymentMethod') <span class="text-red-500">{{ $message }}</span> @enderror

<!-- Discount -->
<div class="mb-4">
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
                <div class="mb-4">
                    <label for="debtor_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user text-gray-500"></i> Debtor Name
                    </label>
                    <input
                        type="text"
                        id="debtor_name"
                        wire:model="debtor_name"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500"
                        placeholder="Enter debtor's name">
                    @error('debtor_name') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="debtor_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone text-gray-500"></i> Debtor Phone
                    </label>
                    <input
                        type="text"
                        id="debtor_phone"
                        wire:model="debtor_phone"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:ring focus:ring-blue-300 focus:border-blue-500"
                        placeholder="Enter debtor's phone">
                    @error('debtor_phone') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none">
                <i class="fas fa-cash-register"></i> Sell
            </button>
        </form>

    @elseif ($view === 'sales')
        <!-- Sales Listing -->
        <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-list text-blue-500 mr-2"></i> Sales Listing
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-200 rounded-lg shadow-sm">
                    <thead class="bg-blue-100 text-gray-700">
                        <tr>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Medicine</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Quantity</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Price Per Unit</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Total Price</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Sold By</th>
                            <th class="border-b border-gray-300 px-4 py-2 text-left font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr class="hover:bg-gray-50">
                                <td class="border-b border-gray-300 px-4 py-2">{{ $sale->medicine->name }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">{{ $sale->quantity_sold }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">Ksh. {{ $sale->price_per_unit }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">Ksh. {{ $sale->total_price }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">{{ $sale->sold_by }}</td>
                                <td class="border-b border-gray-300 px-4 py-2">
                                    <button
                                        wire:click="editSale({{ $sale->id }})"
                                        class="text-blue-500 hover:text-blue-700 px-2 py-1 rounded bg-blue-50 hover:bg-blue-100 transition">
                                        Edit
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
    @endif
</div>

