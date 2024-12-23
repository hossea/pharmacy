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
    public $debtors;

    public function mount()
    {
        $this->loadData();
        $this->total_price = 0;
        $this->debtors = Debtor::with('sale.medicine')->get();
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
            'paymentMethod' => 'required|in:Mpesa,Cash,Debt,Bank Transfer',
            'discount' => 'nullable|numeric|min:0',
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
        $discount = $this->discount ?? 0;

        if ($discount > ($this->pricePerUnit * $this->quantity)) {
            session()->flash('error', 'Discount cannot exceed the total price.');
            return;
        }

        $this->totalPrice = ($this->pricePerUnit * $this->quantity) - $discount;

        if ($this->totalPrice < 0) {
            session()->flash('error', 'Total price cannot be negative.');
            return;
        }

        $medicine->decrement('quantity', $this->quantity);

        $sale = Sale::create([
            'medicine_id' => $this->selectedMedicineId,
            'quantity_sold' => $this->quantity,
            'price_per_unit' => $this->pricePerUnit,
            'total_price' => $this->totalPrice,
            'payment_method' => $this->paymentMethod,
            'discount' => $discount,
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

    public function toggleDebtorStatus($debtorId)
    {
        $debtor = Debtor::find($debtorId);

        if (!$debtor) {
            session()->flash('error', 'Debtor not found.');
            return;
        }

        $debtor->status = $debtor->status === 'Complete' ? 'Incomplete' : 'Complete';
        $debtor->save();

        $this->debtors = Debtor::with('sale.medicine')->get();
        session()->flash('message', 'Debtor status updated successfully.');
    }


    public function storeDebtor(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'amount_owed' => 'required|numeric',
            'sale_id' => 'required|exists:sales,id',
        ]);

        $debtor = Debtor::create($validatedData);

        return response()->json(['message' => 'Debtor saved successfully!', 'debtor' => $debtor]);
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

    public function calculateSalesSummary()
    {
        $sales = Sale::all();

        $totals = [
            'cash' => 0,
            'mpesa' => 0,
            'bank_transfer' => 0,
            'debt' => 0,
            'total_discount' => 0,
            'overall_total' => 0,
        ];

        foreach ($sales as $sale) {
            $totals['total_discount'] += $sale->discount ?? 0;
            $totals['overall_total'] += $sale->total_price;

            switch ($sale->payment_method) {
                case 'Cash':
                    $totals['cash'] += $sale->total_price;
                    break;
                case 'Mpesa':
                    $totals['mpesa'] += $sale->total_price;
                    break;
                case 'Bank Transfer':
                    $totals['bank_transfer'] += $sale->total_price;
                    break;
                case 'Debt':
                    $totals['debt'] += $sale->total_price;
                    break;
            }
        }

        return $totals;
    }

    public function deleteSale($salesId)
    {
        $sale = Sale::findOrFail($salesId);
        $sale->delete();
        $this->sales = Sale::all();

        session()->flash('message', 'Sale deleted successfully.');
    }

    public function render()
    {
        return view('livewire.sales-management')->layout('layouts.app');
    }

}
