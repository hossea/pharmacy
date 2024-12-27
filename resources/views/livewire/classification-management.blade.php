<div class="p-6 bg-white shadow rounded">
    <h2 class="text-lg font-bold mb-4">Classification Management</h2>

    <!-- Form -->
    <form wire:submit.prevent="{{ $isEditing ? 'updateClassification' : 'createClassification' }}" class="mb-6">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Classification Name">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
            <select id="category_id" wire:model="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Select a Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded shadow">
            {{ $isEditing ? 'Update' : 'Create' }}
        </button>
    </form>

    <!-- List -->
    <table class="min-w-full table-auto bg-white border rounded">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Category</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($classifications as $classification)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $classification->name }}</td>
                    <td class="px-4 py-2">{{ $classification->category->name }}</td>
                    <td class="px-4 py-2">
                        <button wire:click="editClassification({{ $classification->id }})" class="text-blue-500">Edit</button>
                        <button wire:click="deleteClassification({{ $classification->id }})" class="text-red-500 ml-4">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $classifications->links() }}
    </div>
</div>
