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

        </div>
    </div>
</x-app-layout>
