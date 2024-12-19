<div>
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Add Category Button -->
    <button
        wire:click="$set('showCategoryModal', true)"
        class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
        + Add Category
    </button>

    <!-- Categories Table -->
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td class="border px-4 py-2">{{ $category->name }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <button
                            wire:click="editCategory({{ $category->id }})"
                            class="bg-yellow-500 text-white px-2 py-1 rounded">
                            Edit
                        </button>
                        <button
                            wire:click="deleteCategory({{ $category->id }})"
                            class="bg-red-500 text-white px-2 py-1 rounded">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Category Modal -->
    @if ($showCategoryModal)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50">
            <div class="bg-white rounded shadow-lg w-96 p-6">
                <h2 class="text-lg font-semibold mb-4">
                    {{ $editCategoryId ? 'Edit Category' : 'Add New Category' }}
                </h2>
                <input
                    type="text"
                    wire:model="{{ $editCategoryId ? 'editCategoryName' : 'newCategoryName' }}"
                    class="border border-gray-300 rounded w-full p-2 mb-4"
                    placeholder="Category Name">
                @error($editCategoryId ? 'editCategoryName' : 'newCategoryName')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
                <div class="flex justify-end space-x-2">
                    <button
                        wire:click="resetModal"
                        class="bg-gray-500 text-white px-4 py-2 rounded">
                        Cancel
                    </button>
                    <button
                        wire:click="{{ $editCategoryId ? 'updateCategory' : 'saveCategory' }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded">
                        Save
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
