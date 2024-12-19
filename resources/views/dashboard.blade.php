<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Sidebar -->
            <div class="w-1/4 bg-white shadow-md rounded-lg p-4">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">Navigation</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ url('/medicine-management') }}"
                           class="flex items-center py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">
                            <i class="fas fa-pills mr-2"></i> Medicine Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/sales-management') }}"
                           class="flex items-center py-2 px-4 bg-green-500 text-white rounded hover:bg-green-600">
                            <i class="fas fa-chart-line mr-2"></i> Sales Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/categories') }}"
                           class="flex items-center py-2 px-4 bg-purple-500 text-white rounded hover:bg-purple-600">
                            <i class="fas fa-th-large mr-2"></i> Categories
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="w-3/4 ml-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Total Sales Cards -->
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-cash-register mr-2 text-green-500"></i> Total Sales
                        </h3>
                        <p>Daily: <span class="text-green-500 font-bold">Ksh 1,200</span></p>
                        <p>Weekly: <span class="text-green-500 font-bold">Ksh 8,400</span></p>
                        <p>Monthly: <span class="text-green-500 font-bold">Ksh 36,000</span></p>
                    </div>

                    <!-- Total Price for Products Sold -->
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-shopping-bag mr-2 text-blue-500"></i> Total Products Sold
                        </h3>
                        <p>Daily: <span class="text-blue-500 font-bold">Ksh 800</span></p>
                        <p>Weekly: <span class="text-blue-500 font-bold">Ksh 5,600</span></p>
                        <p>Monthly: <span class="text-blue-500 font-bold">Ksh 24,000</span></p>
                    </div>

                    <!-- Debts -->
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-hand-holding-usd mr-2 text-red-500"></i> Debts
                        </h3>
                        <p>Total: <span class="text-red-500 font-bold">Ksh 2,000</span></p>
                    </div>

                    <!-- Discounts in Cash -->
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-percentage mr-2 text-purple-500"></i> Discounts
                        </h3>
                        <p>Daily: <span class="text-purple-500 font-bold">Ksh 50</span></p>
                        <p>Weekly: <span class="text-purple-500 font-bold">Ksh 350</span></p>
                        <p>Monthly: <span class="text-purple-500 font-bold">Ksh 1,500</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
