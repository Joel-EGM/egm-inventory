<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Branch;
use App\Models\OrderDetail;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use Livewire\WithPagination;
use Carbon\Carbon;

class WireHistory extends Component
{
    use ModalVariables;
    use WireVariables;
    use WithPagination;

    public $month = 0;
    public $year = 0;

    public function mount()
    {
        $this->branches = Branch::select('id', 'branch_name')->get();

        $this->orders = Order::select('id', 'branch_id', 'order_date', 'order_status', 'or_number', 'updated_at')->get();

        $this->order_date = Carbon::now()->format('Y-m');


        $listBranches = $this->branches->pluck('id');
        $this->filteredBranches = $this->orders
            ->where('order_status', '=', 'received')
            ->whereIn('branch_id', $listBranches)
            ->unique('branch_id');

        $this->filteredBranches
            ->values()
            ->all();
    }

    public function render()
    {
        $user = Auth()->user()->branch_id;
        $page = (int) $this->paginatePage;
        $filtered = $this->orders->filter(function ($value) {
            if(Auth()->user()->branch_id === 1 || Auth()->user()->branch_id === 41) {
                return $value->branch_id === (int) $this->sortList;

            } else {
                return $value->branch_id === Auth()->user()->branch_id;
            }
        });
        $gg = $filtered->all();
        if(Auth()->user()->branch_id === 1 || Auth()->user()->branch_id === 41) {
            if($this->sortList === 'all') {

                if(Auth()->user()->branch_id === 1) {
                    $orderHistory = Order::whereHas('branches', function ($query) {
                        $query->where('branch_name', 'like', '%' . $this->search . '%')
                        ->where('area_number', '=', 0);
                    })
                    ->where('order_status', '=', 'received')
                    ->orderBy('order_date', 'DESC')
                    ->orderBy('updated_at', 'DESC')
                    ->paginate($page);

                }
                if(Auth()->user()->branch_id === 41) {

                    $orderHistory = Order::whereHas('branches', function ($query) {
                        $query->where('branch_name', 'like', '%' . $this->search . '%')
                        ->whereRaw('area_number IN (4,5,6) OR branch_id = 41');
                    })
                    ->where('order_status', '=', 'received')
                    ->orderBy('order_date', 'DESC')
                    ->orderBy('updated_at', 'DESC')
                    ->paginate($page);
                }
            } else {

                $orderHistory = collect($gg)->where('order_status', '=', 'received')
                    ->sortBy(
                        [
                    ['order_date','DESC'],
                    ['updated_at','DESC']]
                    )
                    ->paginateArray($page);
            }

        } else {
            $orderHistory =
            collect($gg)
                ->where('order_status', '=', 'received')
                ->sortBy(
                    [
                    ['order_date','DESC'],
                    ['updated_at','DESC']
                    ]
                )
                ->paginateArray($page);
        }

        return view('livewire.history', [
            'orderHistory' => $orderHistory,
        ]);

    }

    private function getOrderInfo($id)
    {
        $this->details = OrderDetail::select(
            'supplier_id',
            'suppliers_name',
            'item_id',
            'item_name',
            'requester',
            'quantity',
            'order_type',
            'price',
            'total_amount',
            'order_details.order_status'
        )
        ->join('orders', 'orders.id', '=', 'order_details.order_id')
        ->join('suppliers', 'suppliers.id', '=', 'order_details.supplier_id')
        ->join('items', 'items.id', '=', 'order_details.item_id')
        ->where('order_id', $id)->get();

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
