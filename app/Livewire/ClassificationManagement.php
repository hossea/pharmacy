<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Classification;
use Livewire\Component;
use Livewire\WithPagination;

class ClassificationManagement extends Component
{
    use WithPagination;

    public $name;
    public $category_id;
    public $classificationId;
    public $categories;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
    ];

    public function mount()
    {
        $this->categories = Category::all();
    }


    public function resetFields()
    {
        $this->name = '';
        $this->category_id = '';
        $this->classificationId = null;
        $this->isEditing = false;
    }

    public function createClassification()
    {
        $this->validate();
        Classification::create([
            'name' => $this->name,
            'category_id' => $this->category_id,
        ]);
        session()->flash('message', 'Classification created successfully.');
        $this->resetFields();
    }

    public function editClassification($id)
    {
        $classification = Classification::findOrFail($id);
        $this->classificationId = $classification->id;
        $this->name = $classification->name;
        $this->category_id = $classification->category_id;
        $this->isEditing = true;
    }

    public function updateClassification()
    {
        $this->validate();
        $classification = Classification::findOrFail($this->classificationId);
        $classification->update([
            'name' => $this->name,
            'category_id' => $this->category_id,
        ]);
        session()->flash('message', 'Classification updated successfully.');
        $this->resetFields();
    }

    public function deleteClassification($id)
    {
        Classification::findOrFail($id)->delete();
        session()->flash('message', 'Classification deleted successfully.');
    }

    public function render()
    {
        return view('livewire.classification-management', [
            'classifications' => Classification::with('category')->paginate(10),
        ])->layout('layouts.app');
    }

}
