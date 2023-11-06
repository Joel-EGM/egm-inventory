<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Item;
use App\Models\ItemPrice;
use App\Models\OrderDetail;
use App\Models\Stock;
use App\Models\User;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use App\Http\Traits\TrackDirtyProperties;
use App\Http\Interfaces\FieldValidationMessage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Carbon\Carbon;

class WireOrder extends Component implements FieldValidationMessage
{
    use ModalVariables;
    use WireVariables;
    use TrackDirtyProperties;
    use WithPagination;

    public $layoutTitle = 'Create Order';
    public $oBranch;
    public $updateID;
    public $itemPrice;
    public $search = '';
    public $filteredSuppliers;
    public $checkStatus;
    public $branchInfo;
    public $createTag;
    public $has_inventory;
    public $selectALL = false;
    public $selectALLOrders = false;
    public $selectedOrders = [];
    public $batchcomplete;
    public $filteredHasInventory;

    protected $rules = [
        'item_id' => 'bail|required',
        'order_date'  => 'bail|required|date',
        'supplier_id' => 'bail|required',
        'branch_id' => 'bail|required',
        'quantity'  => 'required|numeric| max: 999',
        'unitPrice'  => 'bail|required|numeric',
        'total_amount'  => 'bail|required|numeric',
    ];

    protected function messages()
    {
        return[
            'selectedRecord.required' => 'Choose at least one item'
        ];
    }

    public function mount()
    {
        $this->items = Item::select('id', 'item_name', 'unit_name', 'pieces_perUnit', 'fixed_unit')->get();

        $this->suppliers = Supplier::select('id', 'suppliers_name')->get();

        $this->branches = Branch::select('id', 'branch_name', 'status', 'has_inventory', 'can_createall')->where('status', '=', '1')->get();

        $this->filteredHasInventory = $this->branches->map->only(['id','branch_name','has_inventory'])->where('has_inventory', '=', '1');

        // dd($this->filteredHasInventory);

        $this->order_details = OrderDetail::all();

        $this->itemPrice = ItemPrice::select('supplier_id', 'item_id', 'price_perUnit', 'price_perPieces')->get();

        $this->stocks = Stock::select('item_id', 'branch_id', 'quantity')->get();

        $this->order_date = Carbon::now()->format('Y-m-d');


        $this->orders = Order::select('id', 'branch_id', 'order_date', 'order_status', 'or_number', 'or_date')->get();

        $user = Auth()->user()->branch_id;

        $this->branchFind = Branch::select('id', 'branch_name', 'has_inventory', 'can_createall')
            ->where('id', $user)
            ->first();

        $this->has_inventory = $this->branchFind->has_inventory;

        $sup_id = $this->itemPrice->pluck('supplier_id');

        $this->filteredSuppliers = $this->suppliers->whereIn('id', $sup_id);

        $listBranches = $this->branches->pluck('id');

        $this->filteredBranches = $this->orders
            ->whereIn('branch_id', $listBranches)
            ->unique('branch_id');

        $this->filteredBranches->values()->all();

    }

    public function render()
    {
        $page = (int)$this->paginatePage;
        $filtered = $this->orders->filter(function ($value) {
            return $value->branch_id === (int)$this->sortList;
        });
        $filteredBranchList = $filtered->all();

        if($this->has_inventory != 1) {

            $this->updatedSupplierId();

            $allorders = Order::whereHas('branches', function ($query) {
                $query->where('order_date', 'like', $this->search . '%');
            })
                ->where('order_status', '!=', 'received')
                ->where('branch_id', Auth()->user()->branch_id)
                ->orderByDesc('created_at')
                ->paginate($page);
        } elseif($this->sortList === 'all') {

            $allorders = Order::whereHas('branches', function ($query) {
                $query->where('branch_name', 'like', '%' . $this->search . '%');
            })
                ->where('order_status', '!=', 'received')
                ->orderByDesc('created_at')
                ->paginate($page);
        } else {

            $allorders = collect($filteredBranchList)
                ->where('order_status', '!=', 'received')
                ->sortByDesc('created_at')
                ->paginateArray($page);
        }

        return view('livewire.order', [
            'allorders' => $allorders ]);

    }

    public function submit()
    {
        if(!$this->orderArrays) {
            $validatedItem = $this->validate();
        }

        if (is_null($this->Index)) {

            $orders = Order::create([
                'branch_id' => $this->branch_id,

                'order_date' => $this->order_date,

                'order_status' => 'pending',

                'or_number' => 0,

                'created_by' => Auth()->user()->name,
            ]);

            foreach ($this->orderArrays as $orderArray) {
                $orders->orderDetails()->create([
                    'order_id' => $orders->id,

                    'supplier_id' => $orderArray['supplier_id'],

                    'item_id' => $orderArray['item_id'],

                    'price' => $orderArray['price'],

                    'quantity' => $orderArray['quantity'],

                    'total_amount' => $orderArray['total_amount'],

                    'order_type' => $orderArray['order_type'],

                    'order_status' => 'pending',

                    'is_received'   => 0,
                ]);
            }
        }

        $this->orders->push($orders);
        $this->clearForm();
        $this->modalToggle();

        $notificationMessage = 'Record successfully created.';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'success',
            'messagePrimary'   => $notificationMessage
        ]);
    }

    public function orderUpdate()
    {
        $orders = Order::where('id', $this->updateID)->update([
            'branch_id' => $this->branch_id,
        ]);

        $index = $this->updateID;

        foreach ($this->orderArrays as $orderArray) {
            OrderDetail::where('order_id', $this->updateID)->updateOrCreate([
                'order_id' => $this->updateID,

                'supplier_id' => $orderArray['supplier_id'],

                'item_id' => $orderArray['item_id'],

                'price' => $orderArray['price'],

                'quantity' => $orderArray['quantity'],

                'total_amount' => $orderArray['total_amount'],

                'order_type' => $orderArray['order_type'],

                'order_status' => 'pending',

                'is_received'   => 0,
            ]);
        }

        $this->orders->push();

        $this->clearForm();

        $this->modalToggle();

        $notificationMessage = 'Record successfully updated.';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'success',
            'messagePrimary'   => $notificationMessage
        ]);
    }

    public function addOrderArray()
    {
        $this->validate();

        $branchName = '';
        foreach ($this->branches as $branch) {
            if ($branch->id === (int) $this->branch_id) {
                $branchName = $branch->branch_name;
                break;
            }
        }

        $supplierName = '';
        foreach ($this->suppliers as $supplier) {
            if ($supplier->id === (int) $this->supplier_id) {
                $supplierName = $supplier->suppliers_name;
                break;
            }
        }

        $itemName = '';
        foreach ($this->items as $item) {
            if ($item->id === (int) $this->item_id) {
                $itemName = $item->item_name;
                break;
            }
        }

        $itemExists = false;
        if(count($this->orderArrays) > 0 || $this->formTitle === 'Edit Order') {


            foreach ($this->orderArrays as $key => $value) {

                if($this->formTitle === 'Edit Order') {
                    $id = $value['id'];
                    $convert = (string)$value['item_id'];
                    $quantityConvert = (string)$value['quantity'];
                    if($convert === $this->item_id) {
                        $this->orderArrays[$key]['quantity'] += $this->quantity;
                        $itemExists = true;
                        OrderDetail::find($id)->delete();
                        break;
                    }
                } else {
                    if($value['item_id'] === $this->item_id) {
                        $this->orderArrays[$key]['quantity'] += $this->quantity;

                        $itemExists = true;
                        break;
                    }
                }
            }

        }

        if(!$itemExists) {
            array_push($this->orderArrays, [
            'id' => 0,
            'order_id' => 0,
            'order_date' => $this->order_date,

            'branch_id'  => $this->branch_id,
            'branch_name'  => $branchName,

            'supplier_id' => $this->supplier_id,
            'suppliers_name' => $supplierName,

            'item_id' => $this->item_id,
            'item_name'  => $itemName,

            'quantity' => $this->quantity,
            'price' => $this->unitPrice,
            'total_amount' => $this->total_amount,

            'order_type' => $this->unitString,
            ]);

            $id = $this->unitPriceID['id'];
            $ipq = ItemPrice::whereId($id);

            if ($this->unitString === "Unit") {
                $price = $this->unitPriceID->price_perUnit;
                if ($this->unitPrice != $price) {
                    $ipq->update(['price_perUnit' => $this->unitPrice,]);
                }
            } else {
                $price = $this->unitPriceID->price_perPieces;
                if ($this->unitPrice != $price) {
                    $ipq->update(['price_perPieces' => $this->unitPrice,]);
                }
            }
        }

        $this->reset([
            'item_id',
            'inStocks',
            'unit_id',
            'quantity',
            'unitPrice',
            'total_amount',
        ]);
    }

    public function removeItem($index)
    {
        unset($this->orderArrays[$index]);
        $this->orderArrays = array_values($this->orderArrays);

    }

    public function deleteItem($index)
    {
        $id = $this->orderArrays[$index]['id'];
        OrderDetail::find($id)->delete();
        unset($this->orderArrays[$index]);
        $this->orderArrays = array_values($this->orderArrays);
    }

    public function clearFormVariables()
    {
        $this->reset([
            'isFormOpen',
            'isDeleteOpen',
            'Index',
            'formTitle',
            'branch_id',
            'item_id',
            'supplier_id',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'branch_id',
            'item_id',
            'supplier_id',
            'unitType',
            'unitName',
            'unit_id',
            'quantity',
            'unitPrice',
            'itemList',
            'total_amount',
            'orderArrays',
        ]);
    }
    public function edit($id, $formAction = null)
    {
        $this->updateID = $id;

        $this->order_date = $this->orders
            ->where('id', $id)
            ->pluck('order_date')
            ->first();

        $this->branch_id = $this->orders
            ->where('id', $id)
            ->pluck('branch_id')
            ->first();



        $this->orderArrays = OrderDetail::with('branches', 'items', 'orders', 'suppliers')
        ->select(
            'order_details.id',
            'order_id',
            'branch_id',
            'branch_name',
            'order_date',
            'supplier_id',
            'suppliers_name',
            "item_id",
            'item_name',
            'order_type',
            "quantity",
            'price',
            'total_amount'
        )
        ->where('order_id', $id)
        ->join('orders', 'orders.id', '=', 'order_details.order_id')
        ->join('branches', 'branches.id', '=', 'orders.branch_id')
        ->join('suppliers', 'suppliers.id', '=', 'order_details.supplier_id')
        ->join('items', 'items.id', '=', 'order_details.item_id')
        ->get()
        ->toArray();

        if (!$formAction) {
            $this->formTitle = 'Edit Order';
            $this->isFormOpen = true;
        }
        // $this->updatedBranchId();

    }

    public function selectArrayItem($index, $formAction = null)
    {
        $this->Index = $index;

        $this->orders = $this->orders[$this->Index]['id'];

        $this->order_date = $this->orders[$this->Index]['order_date'];

        $this->branch_id = $this->orders[$this->Index]['branch_id'];

        $this->supplier_id = $this->order_details[$this->Index]['supplier_id'];

        $this->item_id = $this->order_details[$this->Index]['item_id'];

        $this->quantity = $this->order_details[$this->Index]['quantity'];

        $this->unitPrice = $this->order_details[$this->Index]['price'];

        $this->total_amount = $this->order_details[$this->Index]['total_amount'];
    }

    private function getOrderInfo($id)
    {
        $this->details = OrderDetail::with('suppliers', 'items')
            ->where('order_id', $id)
            ->get();

        $this->getBranchID = Order::where('id', $id)
            ->pluck('branch_id')
            ->first();

        $this->getOrderID = Order::where('id', $id)->pluck('id');
    }

    public function viewOrderDetails($id, $formAction = null)
    {
        $this->getOrderInfo($id);
        if (!$formAction) {
            $this->formTitle = 'Order Details';
            $this->isFormOpen = true;
        }
    }

    public function viewDetails($id, $formAction = null)
    {
        $this->getOrderInfo($id);
        if (!$formAction) {
            $this->formTitle = 'View Details';
            $this->isFormOpen = true;
        }
    }

    public function saveMethod()
    {

        $this->checkStatus = $this->details
            ->pluck('order_status')
            ->toArray();

        if(in_array("pending", $this->checkStatus)) {
            $validatedData = $this->validate([
                'selectedRecord' => 'required',
               ]);
        }

        $hasInventory = $this->branches->where('id', $this->getBranchID)->pluck('has_inventory')->first();

        if ($hasInventory != 1) {
            $this->substractBranchOrder();
        } else {
            $this->saveCheckedItems();
        }

        // $converted = $this->getOrderID->first();

        // $OR = $this->orders->where('id', $converted)
        //     ->pluck('or_number')
        //     ->first();

        // if($OR === 0) {
        //     Order::where('id', $converted)->update([
        //         'or_number' => $converted,
        //         'or_date' => Carbon::now()->format('Y-m-d')
        //     ]);
        // }
        $this->reset(['selectedRecord']);
    }

    private function saveCheckedItems()
    {
        if(in_array("pending", $this->checkStatus)) {
            $validatedData = $this->validate([
                'selectedRecord' => 'required',
               ]);
        }

        $getid = OrderDetail::whereIn('id', $this->selectedRecord)
            ->get()
            ->toArray();

        $getDataArray = collect($getid);

        $getSelectedItem = $getDataArray
            ->pluck('item_id')
            ->first();

        $getQuantity = $this->items
            ->where('id', $getSelectedItem)
            ->pluck('pieces_perUnit')
            ->first();

        $checkFixedUnit = $this->items
            ->where('id', $getSelectedItem)
            ->pluck('fixed_unit')
            ->first();

        if($this->getBranchID != 1) {
            $orderItems = OrderDetail::whereIn('id', $this->selectedRecord)->get();

            foreach ($orderItems as $orderItem) {

                $orderType = $orderItem->order_type;

                $itemPieces = Item::where('id', $orderItem->item_id)
                    ->pluck('pieces_perUnit')
                    ->first();

                (int) $itemQty = (($orderType === 'Unit') ? $itemPieces : 1) * (int) $orderItem->quantity;

                $stockItems = Stock::where('item_id', $orderItem->item_id)
                    ->where('quantity', '>', 0)
                    ->orderBy('created_at')
                    ->get();

                foreach ($stockItems as $stockItem) {
                    if ($itemQty > (int)$stockItem->quantity) {

                        Stock::where('id', (int)$stockItem->id)->update([
                             'quantity' => 0
                         ]);

                        $itemQty -= $stockItem->quantity;

                    } else {

                        Stock::where('id', $stockItem->id)->decrement('quantity', $itemQty);
                        break;
                    }
                }
            }

            foreach ($getid as $detail) {
                Stock::create([
                    'branch_id' => $this->getBranchID,

                    'order_id' => $detail['order_id'],

                    'item_id' => $detail['item_id'],

                    'quantity' => $orderType === 'Unit' ? $detail['quantity'] * $getQuantity : $detail['quantity'],

                    'price' => $detail['price'],
                ]);
            }

        } else {

            foreach ($getid as $detail) {
                Stock::create([
                    'branch_id' => $this->getBranchID,

                    'order_id' => $detail['order_id'],

                    'item_id' => $detail['item_id'],

                    'quantity' => $checkFixedUnit === 1 ? $detail['quantity'] * $getQuantity : $detail['quantity'],

                    'price' => $detail['price'],
                ]);
            }
        }


        OrderDetail::whereIn('id', $this->selectedRecord)->update([
            'order_status' => 'received',
            'is_received' => 1
        ]);

        $statusUpdate = Order::where('id', $this->getOrderID);

        if ($this->completedOrder === true) {
            $statusUpdate->update([
                             'order_status' => 'received',
                         ]);
        } else {
            $statusUpdate->update([
                'order_status' => 'incomplete',
            ]);
        }

        $this->modalToggle();
        $this->mount();
        $notificationMessage = 'Items has been received.';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'success',
            'messagePrimary'   => $notificationMessage
        ]);
    }

    private function substractBranchOrder()
    {


        if(in_array("pending", $this->checkStatus)) {
            $validatedData = $this->validate([
                'selectedRecord' => 'required',
               ]);
        }


        $orderItems = OrderDetail::whereIn('id', $this->selectedRecord)->get();
        foreach ($orderItems as $orderItem) {
            $orderType = $orderItem->order_type;

            $itemPieces = Item::where('id', $orderItem->item_id)
                ->pluck('pieces_perUnit')
                ->first();

            (int) $itemQty = (($orderType === 'Unit') ? $itemPieces : 1) * (int) $orderItem->quantity;

            $stockItems = Stock::where('item_id', $orderItem->item_id)
                ->where('quantity', '>', 0)
                ->where('branch_id', $orderItem->supplier_id)
                ->orderBy('created_at')
                ->get();

            foreach ($stockItems as $stockItem) {

                Stock::where('id', $stockItem->id)
                    ->where('branch_id', $orderItem->supplier_id)
                    ->update([
                        'qty_out' => $stockItem->qty_out - $itemQty
                    ]);

            }
        }

        OrderDetail::whereIn('id', $this->selectedRecord)->update([
            'order_status' => 'received',
            'is_received' => 1
        ]);

        $statusUpdate = Order::where('id', $this->getOrderID);

        if ($this->completedOrder === true) {
            $statusUpdate->update([
                             'order_status' => 'received',
                         ]);
        } else {
            $statusUpdate->update([
                'order_status' => 'incomplete',
            ]);
        }

        $this->modalToggle();
        $this->reset(['selectedRecord']);
        $this->mount();
    }

    public function modalDelete($id, $formAction = null)
    {
        $this->deleteID = $this->orders->where('id', $id)->pluck('id');

        if ($formAction) {
            $this->formTitle = 'Delete Order';
            $this->isDeleteOpen = true;
        }
    }

    public function deleteArrayItem()
    {

        $index = $this->deleteID;

        $data = Order::leftJoin('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('orders.id', $index);

        OrderDetail::where('order_id', $index)->delete();

        $data->delete();

        $this->modalToggle('Delete');

        $notificationMessage2 = 'Record successfully deleted.';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'error',
            'messagePrimary'   => $notificationMessage2
        ]);
    }

    public function modalToggle($formAction = null)
    {
        $this->clearForm();

        if (!$formAction) {
            if ($this->Index === null) {
                $this->formTitle = 'Create Order';
                if(Auth()->user()->branch_id != 1) {
                    $this->branch_id = Auth()->user()->branch_id;
                }
            }

            $this->isFormOpen = !$this->isFormOpen;
            $this->clearAndResetForm();
        } else {
            $this->isDeleteOpen = !$this->isDeleteOpen;
            $this->clearAndResetDelete();
        }
    }


    public function resetForm()
    {
        $this->reset([
            'item_id',
            'unitType',
            'unitName',
            'unit_id',
            'quantity',
            'unitPrice',
            'itemList',
            'total_amount',
        ]);
    }

    public function updatedBranchId()
    {
        $this->reset([
            'unitType',
            'unitName',
            'unit_id',
            'quantity',
            'unitPrice',
            'itemList',
            'total_amount',
        ]);

        $this->createTag = $this->branches->where('id', $this->branch_id)->pluck('can_createall')->first();

        if($this->branch_id != 1) {
            $this->supplier_id = 1;
            $this->updatedSupplierId();
        }

    }

    public function updatedSupplierId()
    {
        $this->resetForm();
        if ($this->supplier_id === "None") {
            $this->reset([
                'item_id',
                'unitType',
                'unitName',
                'unit_id',
                'quantity',
                'unitPrice',
                'itemList',
                'total_amount',
            ]);
            return;
        }
        if ($this->branch_id != 1) {
            $items = Item::with('itemprices')->get();

            $branch = $this->branches
                ->where('id', $this->branch_id)
                ->pluck('branch_name')
                ->first();

            $branchForm = $items->filter(function ($value) use ($branch) {
                return str_contains($value->item_name, $branch)
                && $value->itemprices->count() > 0;
            })->all();

            $universalForm = $items->filter(function ($value) use ($branch) {
                return (!str_starts_with($value->item_name, "Or ")
                && !str_starts_with($value->item_name, "Check Voucher"))
                && $value->itemprices->count() > 0;
            })->all();

            $sort = collect(array_merge($branchForm, $universalForm))->sortBy('item_name');

            $this->itemList = $sort->values()->all();

        } else {
            $collection = ItemPrice::where('supplier_id', (int) $this->supplier_id)->get();

            $filtered = $collection->filter(function ($value, $key) {
                return $value->count() > 0;
            });

            $this->itemList = $filtered->all();
        }
    }

    public function updatedItemId()
    {
        $this->reset([
                'quantity',
                'total_amount',
                'inStocks',
        ]);
        $this->unitName = $this->items->where('id', (int) $this->item_id);
        $auth_branch = Auth()->user()->branch_id;

        $this->inStocks = $this->stocks->where('item_id', $this->item_id)->where('branch_id', $auth_branch)->sum('quantity');
        if ($this->unitName->pluck('fixed_unit')->first() === 1) {

            $this->unitString = "Unit";

            $this->inStocks = $this->stocks->where('item_id', $this->item_id)->where('branch_id', $auth_branch)->sum('quantity');


            $this->unitPriceID = $this->userBranch != 1 ?
                    ItemPrice::where('item_id', $this->item_id)->first() :
                    ItemPrice::where('item_id', $this->item_id)->where('supplier_id', $this->supplier_id)->first();

            $this->loadPrice();
        }
    }

    public function updatedUnitId()
    {
        if ($this->unit_id === "None") {
            $this->reset([
                'quantity',
                'unitPrice',
                'total_amount',
            ]);
            return;
        }

        $explodeResult = explode(' ', $this->unit_id);

        $unitId = (int) $explodeResult[0];

        $this->unitString = $explodeResult[1];

        if($this->userBranch != 1) {
            $this->unitPriceID = ItemPrice::select('price_perUnit', 'price_perPieces')
                ->where('item_id', (int) $unitId)
                ->orderby('created_at', 'ASC')
                ->first();

            $this->unitPrice = $this->unitPriceID->price_perPieces;

        } else {
            $this->unitPriceID = ItemPrice::where('item_id', (int) $unitId)
                ->where('supplier_id', $this->supplier_id)
                ->first();

            $this->unitPrice = $this->unitPriceID->price_perPieces;
        }

        if ($this->unitString === "Unit") {
            $this->unitPrice = $this->unitPriceID->price_perUnit;
        }

        if ($this->quantity > 0) {
            $this->computeTotalAmount();
        }
    }

    public function loadPrice()
    {
        if($this->userBranch != 1) {
            $unitPrice = ItemPrice::where('item_id', (int) $this->item_id)->min('price_perUnit');
            $this->unitPrice = $unitPrice;

        } else {
            $unitPrice = ItemPrice::where('item_id', (int) $this->item_id)
                ->where('supplier_id', $this->supplier_id)
                ->first();

            $this->unitPrice = $unitPrice->price_perUnit;
        }

    }

    public function updatedQuantity()
    {
        $this->computeTotalAmount();
    }

    private function computeTotalAmount()
    {
        $this->total_amount = number_format((int) $this->quantity * $this->unitPrice, 2, '.', '');
    }

    public function updatedSelectAll($value)
    {
        if($value) {
            $this->selectedRecord = $this->details->pluck('id');
        } else {
            $this->selectedRecord = [];
        }
    }

    public function updatedSelectAllOrders($value)
    {
        if($value) {
            $this->selectedOrders = $this->orders->where('order_status', '!=', 'received')->where('order_completed', '!=', '1')->pluck('id');
        } else {
            $this->selectedOrders = [];
        }
    }

    public function batchcomplete()
    {
        $auth_branch = Auth()->user()->branch_id;
        $orderItems = OrderDetail::whereIn('order_id', $this->selectedOrders)->get();

        foreach ($orderItems as $orderItem) {

            $orderType = $orderItem->order_type;

            OrderDetail::where('order_id', $orderItem->order_id)->update([
                'order_status' => 'completed'
            ]);
            $itemPieces = Item::where('id', $orderItem->item_id)
                ->pluck('pieces_perUnit')
                ->first();

            (int) $itemQty = (($orderType === 'Unit') ? $itemPieces : 1) * (int) $orderItem->quantity;

            $stockItems = Stock::where('item_id', $orderItem->item_id)
                ->where('quantity', '>', 0)
                ->where('branch_id', $auth_branch)
                ->orderBy('created_at')
                ->get();

            foreach ($stockItems as $stockItem) {
                if ($itemQty > (int)$stockItem->quantity) {

                    Stock::where('id', (int)$stockItem->id)->update([
                         'quantity' => 0,
                         'qty_out' => $itemQty
                     ]);

                    $itemQty -= $stockItem->quantity;

                } else {

                    Stock::where('id', $stockItem->id)
                        ->where('branch_id', $auth_branch)
                        ->update([
                            'quantity' => $stockItem->quantity - $itemQty,
                            'qty_out' => $itemQty
                        ]);

                    break;
                }
            }

            foreach($this->selectedOrders as $selectedOrder) {

                DB::table('orders')
                    ->where('id', $selectedOrder)
                    ->update([
                        'received_by' => Auth()->user()->branch_id,
                        'order_completed' => 1,
                        'or_date' => Carbon::now()->format('Y-m-d'),
                        'or_number' => $selectedOrder
                    ]);
            }

        }
    }
}
