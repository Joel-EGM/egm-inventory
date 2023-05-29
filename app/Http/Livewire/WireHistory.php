<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Branch;
use App\Models\OrderDetail;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use Livewire\WithPagination;

class WireHistory extends Component
{
    use ModalVariables;
    use WireVariables;
    use WithPagination;


    public function mount()
    {
        $this->branches = Branch::all();

        $this->order_details = OrderDetail::all();

        $this->orders = Order::all();

        $listBranches = $this->branches->pluck('id');
        $this->filteredBranches = $this->orders->where('order_status', '=', 'received')->whereIn('branch_id', $listBranches)->unique('branch_id');

        $this->filteredBranches->values()->all();
    }

    public function render()
    {
        $page = (int)$this->paginatePage;
        $filtered = $this->orders->filter(function ($value) {
            return $value->branch_id === (int)$this->sortList;
        });
        $gg = $filtered->all();
        if($this->sortList === 'all') {
            return view('livewire.history', [
                'orderHistory' =>
                Order::whereHas('branches', function ($query) {
                    $query->where('branch_name', 'like', '%'.$this->search.'%');
                })->where('order_status', '=', 'received')->paginate($page),
            ]);
        } else {
            return view('livewire.history', [
                'orderHistory' =>
                collect($gg)->where('order_status', '=', 'received')->paginateArray($page),
            ]);
        }
    }

    private function getOrderInfo($id)
    {
        $this->details = OrderDetail::with('suppliers', 'items')->where('order_id', $id)->get();
        $this->getOrderID = Order::where('id', $id)->pluck('id');
        $this->getBranchID = Order::where('id', $id)->pluck('branch_id')->first();
    }

    public function viewDetails($id, $formAction = null)
    {
        $this->getOrderInfo($id);
        if (!$formAction) {
            $this->formTitle = 'View Details';
            $this->isFormOpen = true;
        }
    }

    public function modalToggle($formAction = null)
    {
        $this->clearForm();
        if (!$formAction) {

            $this->isFormOpen = !$this->isFormOpen;
            $this->clearAndResetForm();
        }
    }

    public function clearFormVariables()
    {
        $this->reset([
            'isFormOpen',
            'isDeleteOpen',
            'Index',
            'formTitle',
        ]);
    }
}
