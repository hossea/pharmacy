<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class Categories extends Component
{
    public $categories; // List of categories
    public $newCategoryName = ''; // Name for new category
    public $editCategoryId = null; // ID of category being edited
    public $editCategoryName = null; // Name of category being edited
    public $showCategoryModal = false; // Controls modal visibility

    protected $rules = [
        'newCategoryName' => 'required|min:3|unique:categories,name',
        'editCategoryName' => 'required|min:3',
    ];

    /**
     * Fetch the list of categories dynamically.
     */
    public function updated()
    {
        $this->categories = Category::all();
    }

    /**
     * Fetch categories on component mount.
     */
    public function mount()
    {
        $this->categories = Category::all();
    }

    /**
     * Save a new category.
     */
    public function saveCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|min:3|unique:categories,name',
        ]);

        Category::create(['name' => $this->newCategoryName]);

        $this->resetModal();
        session()->flash('message', 'Category added successfully!');
    }

    /**
     * Open edit modal with category data.
     */
    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->editCategoryId = $category->id;
        $this->editCategoryName = $category->name;
        $this->showCategoryModal = true;
    }

    /**
     * Update an existing category.
     */
    public function updateCategory()
    {
        $this->validate([
            'editCategoryName' => 'required|min:3|unique:categories,name,' . $this->editCategoryId,
        ]);

        $category = Category::findOrFail($this->editCategoryId);
        $category->update(['name' => $this->editCategoryName]);

        $this->resetModal();
        session()->flash('message', 'Category updated successfully!');
    }

    /**
     * Delete a category.
     */
    public function deleteCategory($id)
    {
        Category::findOrFail($id)->delete();

        $this->categories = Category::all();
        session()->flash('message', 'Category deleted successfully!');
    }

    /**
     * Reset modal data.
     */
    public function resetModal()
    {
        $this->newCategoryName = '';
        $this->editCategoryId = null;
        $this->editCategoryName = null;
        $this->showCategoryModal = false;

        $this->categories = Category::all();
    }

    /**
     * Render the component view.
     */
    public function render()
    {
        return view('livewire.categories', [
            'categories' => $this->categories,
        ])->layout('layouts.app');
    }
}
