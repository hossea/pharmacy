<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Medicine;
use App\Models\Category;
use App\Models\MedicineStock;
use Livewire\WithPagination; // Add this

class MedicineManagement extends Component
{
    use WithPagination; // Add this
    public $currentPage = 'list';
    public $editMode = false;

    public $medicine_id, $name, $company, $quantity, $price;
    public $medicines;

    public $showDeleteModal = false;
    public $deleteMedicineId, $deleteMedicineName;

    public $currentMedicineId, $currentMedicineName, $newStock;
    public $categories;
    public $category_id;
    public $expiry_date;
    public $stocks = [];
    public $filterMonth;
    public $filterDate;

    public function mount()
    {
        $this->categories = Category::all();
        $this->medicines = Medicine::with('category')->get();
        $this->loadStocks();
    }

    public function updatedFilterMonth()
    {
        $this->resetPage();
        $this->loadStocks();
    }
    public function updatedStartDate()
    {
        $this->resetPage();
        $this->loadStocks();
    }

    public function resetFilter()
    {
        $this->filterMonth = null;
        $this->filterDate = null;
        $this->stocks = MedicineStock::with('medicine')->latest()->get();
        $this->loadStocks();
    }

    public function loadStocks()
    {
        $this->stocks = MedicineStock::with('medicine')
        ->when($this->filterMonth, function ($query) {
                $query->whereMonth('created_at', $this->filterMonth);
            })
            ->when($this->filterDate, function ($query) {
                $query->whereDate('created_at', $this->filterDate);
            })
            ->latest()
            ->get();
    }
    public function getStocksProperty()
    {
        $query = MedicineStock::query()->with('medicine');

        if ($this->filterMonth) {
            $query->whereMonth('created_at', $this->filterMonth);
        }

        if ($this->filterDate) {
            $query->whereDate('created_at', $this->filterDate);
        }

        return $query->paginate(10);
    }



    public function resetInputs()
    {
        $this->editMode = false;
        $this->medicine_id = $this->name = $this->company = $this->quantity = $this->price = null;
        $this->currentMedicineId = $this->currentMedicineName = $this->newStock = null;
    }

    public function switchPage($page)
    {
        $this->currentPage = $page;

        if ($page !== 'form' || !$this->editMode) {
            $this->resetInputs();
        }
    }
    public function saveMedicine()
    {
        $this->validate([
            'medicine_id' => 'required|unique:medicines,medicine_id',
            'name' => 'required',
            'company' => 'required',
            'quantity' => 'required|numeric|min:1',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'expiry_date' => 'required|date|after:today',
        ]);

        $medicine = Medicine::create([
            'medicine_id' => $this->medicine_id,
            'name' => $this->name,
            'company' => $this->company,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'expiry_date' => $this->expiry_date
        ]);

        session()->flash('message', 'Medicine added successfully!');
        $this->switchPage('list');
        $this->medicines = Medicine::all();
    }

    public function editMedicine($id)
    {
        $medicine = Medicine::findOrFail($id);

        $this->medicine_id = $medicine->medicine_id;
        $this->name = $medicine->name;
        $this->company = $medicine->company;
        $this->quantity = $medicine->quantity;
        $this->price = $medicine->price;

        $this->editMode = true;
        $this->switchPage('form');
    }

    public function updateMedicine()
    {
        $this->validate([
            'name' => 'required',
            'company' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'expiry_date' => 'required|date',
        ]);

        $medicine = Medicine::where('medicine_id', $this->medicine_id)->firstOrFail();

        $medicine->update([
            'name' => $this->name,
            'company' => $this->company,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'expiry_date' => $this->expiry_date,
        ]);

        session()->flash('message', 'Medicine updated successfully!');
        $this->switchPage('list');
        $this->medicines = Medicine::all();
    }
    public function cancelEdit()
    {
        $this->editMode = false;
        $this->resetInputs();
        session()->flash('message', 'Editing canceled.');
        $this->switchPage('list');
    }
    public function switchToAddStock($id)
    {
        $medicine = Medicine::findOrFail($id);
        $this->currentMedicineId = $medicine->id;
        $this->currentMedicineName = $medicine->name;
        $this->newStock = null;
        $this->currentPage = 'addStock';
    }
    public function addStockToDatabase()
    {
        $this->validate(
            [
            'newStock' => 'required|numeric|min:1',
        ]);

        $medicine = Medicine::findOrFail($this->currentMedicineId);
        $medicine->quantity += $this->newStock;
        $medicine->save();

        // Log stock update to MedicineStock
        MedicineStock::create(
            [
           'medicine_id' => $medicine->id,
           'quantity' => $this->newStock,
         ]);

        session()->flash('message', "{$this->newStock} units added to {$medicine->name}!");
        $this->cancelAddStock();
        $this->medicines = Medicine::all();
    }
    public function cancelAddStock()
    {
        $this->currentPage = 'list';
        $this->resetInputs();
    }

    public function switchToStocksAdded()
    {
        $this->currentPage = 'stocksAdded';
        $this->stocks = MedicineStock::with('medicine')->latest()->get();
    }

    public function promptDelete($id, $name)
    {
        $this->deleteMedicineId = $id;
        $this->deleteMedicineName = $name;
        $this->showDeleteModal = true;
    }
    public function confirmDelete()
    {
        $medicine = Medicine::findOrFail($this->deleteMedicineId);
        $medicine->delete();

        session()->flash('message', "{$this->deleteMedicineName} deleted successfully.");
        $this->showDeleteModal = false;
        $this->medicines = Medicine::all();
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteMedicineId = null;
        $this->deleteMedicineName = null;
    }

    public function navigateToSales()
    {
        return redirect()->route('sales-management'); // Adjust 'sales.index' to match your route name
    }
    public function deleteMedicine($medicineId)
    {
        $medicine = Medicine::findOrFail($medicineId);
        $medicine->delete();

        $this->medicines = $this->medicines->filter(function ($medicine) use ($medicineId) {
            return $medicine->id !== $medicineId;
        });

        session()->flash('message', 'Medicine deleted successfully.');
    }

    public function render()
    {
        return view('livewire.medicine-management')->layout('layouts.app');
    }
}
