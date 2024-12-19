<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\Debtor;

class SalesManagement extends Component
{
    public $view = 'sell';
    public $medicines;
    public $sales;
    public $selectedMedicineId;
    public $quantity = 0;
    public $pricePerUnit = 0;
    public $soldBy;
    public $totalPrice = 0;
    public $paymentMethod= '';
    public $discount = 0;
    public $debtorName;
    public $debtorPhone;
    public $price_per_unit;
    public $saleId;
    public $total_price = 0;

    public function mount()
    {
        $this->loadData();
        $this->total_price = 0;
    }

    public function loadData()
    {
        $this->medicines = Medicine::all();
        $this->sales = Sale::with(['medicine', 'debtor'])->get();
    }

    public function switchView($view)
    {
        $this->view = $view;
        if ($view === 'sales') {
            $this->loadData();
        }
    }

    public function sellMedicine()
    {
        $this->validate([
            'selectedMedicineId' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'soldBy' => 'required|string|max:255',
            'paymentMethod' => 'required|in:Mpesa,Cash,Debt,Discount',
        ]);

        $medicine = Medicine::find($this->selectedMedicineId);

        if (!$medicine) {
            session()->flash('error', 'Invalid medicine selection.');
            return;
        }

        if ($this->quantity > $medicine->quantity) {
            session()->flash('error', 'Out of stock! Available quantity is ' . $medicine->quantity);
            return;
        }

        $this->pricePerUnit = $medicine->price;
        $this->totalPrice = $this->quantity * $this->pricePerUnit;
        $this->totalPrice = $this->quantity * $this->pricePerUnit - $this->discount;


        $medicine->decrement('quantity', $this->quantity);

        $sale = Sale::create([
            'medicine_id' => $this->selectedMedicineId,
            'quantity_sold' => $this->quantity,
            'price_per_unit' => $this->pricePerUnit,
            'total_price' => $this->totalPrice,
            'payment_method' => $this->paymentMethod,
            'discount' => $this->discount,
            'sold_by' => $this->soldBy ?? auth()->user()->name,
        ]);
        
        if (!$sale) {
            session()->flash('error', 'Sale record creation failed.');
            return;
        }

        if ($this->paymentMethod === 'Debt') {
            $this->validate([
                'debtorName' => 'required|string|max:255',
                'debtorPhone' => 'nullable|string|max:15',
            ]);

            Debtor::create([
                'name' => $this->debtorName,
                'phone' => $this->debtorPhone,
                'amount_owed' => $this->totalPrice,
                'sale_id' => $sale->id,
            ]);
        }

        $this->resetInputFields();
        $this->loadData();
        session()->flash('message', 'Sale completed successfully.');
    }

    public function editSale($saleId)
    {
        $sale = Sale::find($saleId);
        if ($sale) {
            $this->saleId = $saleId;
            $this->selectedMedicineId = $sale->medicine_id;
            $this->quantity = $sale->quantity_sold;
            $this->pricePerUnit = $sale->price_per_unit;
            $this->soldBy = $sale->sold_by;
            $this->totalPrice = $sale->total_price;
            $this->view = 'edit-sale';
        }


    }

    public function updateSale()
    {
        $this->validate([
            'selectedMedicineId' => 'required',
            'quantity' => 'required|integer|min:1',
            'soldBy' => 'required|string|max:255',
        ]);

        $sale = Sale::find($this->saleId);
        $medicine = Medicine::find($this->selectedMedicineId);

        if (!$sale || !$medicine) {
            session()->flash('error', 'Invalid sale or medicine record.');
            return;
        }

        $originalQuantity = $sale->quantity_sold;
        $adjustment = $originalQuantity - $this->quantity;

        if ($adjustment > 0) {
            $medicine->increment('quantity', $adjustment);
        } elseif ($adjustment < 0) {
            $deduction = abs($adjustment);
            if ($deduction > $medicine->quantity) {
                session()->flash('error', 'Insufficient stock to increase sale quantity.');
                return;
            }
            $medicine->decrement('quantity', $deduction);
        }

        $this->pricePerUnit = $medicine->price;
        $this->totalPrice = $this->quantity * $this->pricePerUnit;

        $sale->update([
            'medicine_id' => $this->selectedMedicineId,
            'quantity_sold' => $this->quantity,
            'price_per_unit' => $this->pricePerUnit,
            'total_price' => $this->totalPrice,
            'sold_by' => $this->soldBy ?? auth()->user()->name,
        ]);

        $this->resetInputFields();
        $this->loadData();
        session()->flash('message', 'Sale updated successfully.');
        $this->switchView('sales');
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['selectedMedicineId', 'quantity'])) {
            $this->updateTotalPrice();
        }
    }
    public function updatedDiscount()
    {
        $this->updateTotalPrice();
    }



    public function updateTotalPrice()
    {
        $medicine = Medicine::find($this->selectedMedicineId);
        if ($medicine) {
            $this->pricePerUnit = $medicine->price;
            $this->totalPrice = $this->quantity > 0 ? $this->quantity * $this->pricePerUnit : 0;
        } else {
            $this->totalPrice = 0;
        }
    }

    public function resetInputFields()
    {
        $this->reset([
            'selectedMedicineId',
            'quantity',
            'soldBy',
            'totalPrice',
            'pricePerUnit',
            'paymentMethod',
            'debtorName',
            'debtorPhone',
        ]);
    }
    public function updatedSelectedMedicineId($medicineId)
    {
        $medicine = Medicine::find($medicineId);
        $this->price_per_unit = $medicine ? $medicine->price : 0;
        $this->updateTotalPrice();
    }

    public function render()
    {
        return view('livewire.sales-management')->layout('layouts.app');
    }
}
