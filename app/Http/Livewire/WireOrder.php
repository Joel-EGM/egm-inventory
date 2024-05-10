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
use Illuminate\Support\Facades\Cache;

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
    public $filteredHOsupplier;

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
            'selectedRecord.required' => 'Choose at least one item',
            'requester' => 'Input name of the Employee/s',
        ];
    }


    public function updated($propertyName)
    {
        $wire_models = [
            'order_id',
            'item_id',
        ];

        if (in_array($propertyName, $wire_models)) {
            $this->$propertyName = ucwords(strtolower($this->$propertyName));
        }

        try {
            $this->validateOnly($propertyName);
        } catch (\Throwable $th) {
        } finally {
            $this->updatedDirtyProperties($propertyName, $this->$propertyName);

        }
    }

    public function mount()
    {

        $collectionItems = Item::select(
            'id',
            'item_name',
            'unit_name',
            'pieces_perUnit',
            'fixed_unit',
            'fixed_pieces'
        )
        ->get();

        $this->items = Cache::rememberForever('items', function () use ($collectionItems) {
            return $collectionItems;
        });


        $collectionSuppliers = Supplier::select(
            'id',
            'suppliers_name',
            'is_ho',
        )
        ->get();

        $this->suppliers = Cache::rememberForever('suppliers', function () use ($collectionSuppliers) {
            return $collectionSuppliers;
        });

        if(Auth()->user()->branch_id === 41) {

            $collectionBranches = Branch::select(
                'id',
                'branch_name',
                'status',
                'has_inventory',
                'can_createall',
                'area_number'
            )
            ->where(
                'status',
                '=',
                '1'
            )
            ->whereRaw('area_number IN (4,5,6) OR id = 41')
            ->get();

        } else {
            $collectionBranches = Branch::select(
                'id',
                'branch_name',
                'status',
                'has_inventory',
                'can_createall',
                'area_number'
            )
            ->where(
                'status',
                '=',
                '1'
            )
            ->get();
        }


        $this->branches = Cache::rememberForever('branches', function () use ($collectionBranches) {

            return $collectionBranches;

        });


        $this->filteredHOsupplier = $this->suppliers
        ->map
        ->only([
            'id',
            'suppliers_name',
            'is_ho','branch_id'
            ])
        ->where(
            'is_ho',
            '=',
            '1'
        );

        $collectionDetails = OrderDetail::select(
            'id',
            'order_id',
            'item_id',
            'quantity',
            'price',
            'total_amount',
            'order_status',
            'is_received',
            'order_type'
        )
        ->get();

        $this->order_details = Cache::remember('branches', 100, function () use ($collectionDetails) {
            return $collectionDetails;
        });


        $this->itemPrice = ItemPrice::all();

        $this->stocks = Stock::select('branch_id', 'item_id', 'category_id', 'quantity', 'qty_out')->get();

        $this->order_date = Carbon::now()->format('Y-m-d');


        $this->orders = Order::select(
            'id',
            'branch_id',
            'order_date',
            'order_status',
            'or_number',
            'or_date'
        )
        ->get();

        $user = Auth()->user()->branch_id;

        $this->branchFind = Branch::select(
            'id',
            'branch_name',
            'has_inventory',
            'can_createall'
        )
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
        $page = (int) $this->paginatePage;
        $filtered = $this->orders->filter(function ($value) {
            return $value->branch_id === (int) $this->sortList;
        });
        $filteredBranchList = $filtered->all();

        if($this->has_inventory != 1) {

            $allorders = Order::whereHas('branches', function ($query) {
                $query->where('order_date', 'like', $this->search . '%');
            })
                ->where('order_status', '!=', 'received')
                ->where('branch_id', Auth()->user()->branch_id)
                ->orderByDesc('created_at')
                ->paginate($page);
        } elseif($this->sortList === 'all') {

            if(Auth()->user()->branch_id === 1) {
                $allorders = Order::whereHas('branches', function ($query) {
                    $query->where('branch_name', 'like', '%' . $this->search . '%')->where('area_number', '=', 0);
                })
                    ->where('order_status', '!=', 'received')
                    ->orderByDesc('created_at')
                    ->paginate($page);

            }
            if(Auth()->user()->branch_id === 41) {

                $allorders = Order::whereHas('branches', function ($query) {
                    $query->where('branch_name', 'like', '%' . $this->search . '%')->whereRaw('area_number IN (4,5,6) OR branch_id = 41');
                })
                    ->where('order_status', '!=', 'received')
                    ->orderByDesc('created_at')
                    ->paginate($page);
            }

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

                    'requester' => $orderArray['requester'],

                    'price' => $orderArray['price'],

                    'quantity' => $orderArray['quantity'],

                    'unit_name' => $orderArray['unit_name'],

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

                'requester' => $orderArray['requester'],

                'price' => $orderArray['price'],

                'quantity' => $orderArray['quantity'],

                'unit_name' => $orderArray['unit_name'],

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
        $unitName = '';
        foreach ($this->items as $item) {
            if ($item->id === (int) $this->item_id) {
                $unitName = $item->unit_name;
                $itemName = $item->item_name;
                break;
            }
        }

        $itemExists = false;
        if(count($this->orderArrays) > 0 || $this->formTitle === 'Edit Order') {


            foreach ($this->orderArrays as $key => $value) {


                if($value['item_id'] === $this->item_id) {
                    $this->orderArrays[$key]['quantity'] += $this->quantity;

                    $itemExists = true;
                    break;
                }
            }

        }
        if(!$itemExists) {
            array_push($this->orderArrays, [

            'order_date' => $this->order_date,

            'branch_id'  => $this->branch_id,
            'branch_name'  => $branchName,

            'supplier_id' => $this->supplier_id,
            'suppliers_name' => $supplierName,

            'item_id' => $this->item_id,
            'item_name'  => $itemName,

            'requester' => $this->requester,

            'unit_name' => ($this->unitString != 'Unit') ? $this->unitString : $unitName,

            'quantity' => $this->quantity,
            'price' => $this->unitPrice,
            'total_amount' => $this->total_amount,

            'order_type' => $this->unitString,
            ]);


        }

        $this->reset([
            'item_id',
            'inStocks',
            'unit_id',
            'quantity',
            'requester',
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
        if(isset($this->orderArrays[$index]['id'])) {
            $id = $this->orderArrays[$index]['id'];
            OrderDetail::find($id)->delete();
            unset($this->orderArrays[$index]);
            $this->orderArrays = array_values($this->orderArrays);
        }
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


        $area_id = $this->branches
        ->where('id', $this->branch_id)
        ->pluck('area_number')
        ->first();

        if($this->branch_id != 1) {
            if($area_id === 0) {
                $this->supplier_id = 1;
            }

            if($area_id != 0) {
                $this->supplier_id = 41;
            }
            $this->filteredSuppliers = $this->suppliers->whereIn('id', $this->supplier_id);
        } else {
            $this->filteredSuppliers = $this->suppliers->whereIn('id', [4,5,6]);
        }

        $this->updatedSupplierId();

        $this->orderArrays = OrderDetail::with('branches', 'items', 'orders', 'suppliers')
        ->select(
            'order_details.id',
            'order_id',
            'branch_name',
            'order_date',
            'supplier_id',
            'suppliers_name',
            'order_details.unit_name',
            'requester',
            'item_id',
            'item_name',
            'order_type',
            'quantity',
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
            $this->updatedBranchId();

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

        $area_id = $this->branches
            ->where('id', $this->branch_id)
            ->pluck('area_number')
            ->first();


        if($this->branch_id != 1) {
            if($area_id === 0) {
                $this->supplier_id = 1;
            }

            if($area_id != 0) {
                $this->supplier_id = 41;
            }
            $this->filteredSuppliers = $this->suppliers->whereIn('id', $this->supplier_id);

        } else {
            $this->filteredSuppliers = $this->suppliers->whereIn('id', [4,5,6]);


        }

        $this->updatedSupplierId();

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
        if ($this->branch_id != 1 && $this->branch_id != 41) {
            $branch = $this->branches
                ->where('id', $this->branch_id)
                ->pluck('branch_name')
                ->first();
            $branchForm = $this->items->filter(function ($value) use ($branch) {
                return str_contains($value->item_name, $branch)
                && $value->itemprices->count() > 0;
            })->where('ho_only', 0)->all();

            $universalForm = $this->items->filter(function ($value) use ($branch) {
                return (!str_starts_with($value->item_name, "Or ")
                && !str_starts_with($value->item_name, "Check Voucher"))
                && $value->itemprices->count() > 0;
            })->where('ho_only', 0)->all();

            $sort = collect(array_merge($branchForm, $universalForm))->sortBy('item_name');

            $this->itemList = $sort->values()->all();

        } else {
            if($this->branch_id != 1) {
                $this->itemList = $this->items;
            } else {
                $collection = $this->itemPrice->where('supplier_id', (int) $this->supplier_id);

                $filtered = $collection->filter(function ($value, $key) {
                    return $value->count() > 0;
                });

                $this->itemList = $filtered->all();
            }
        }


    }

    public function updatedItemId()
    {

        $this->reset([
                'quantity',
                'total_amount',
                'unitPrice',
                'inStocks',
        ]);
        $this->unitName = $this->items->where('id', $this->item_id);

        if ($this->unitName->pluck('fixed_unit')->first() === 1) {

            $this->unitString = "Unit";
            $this->loadPrice();
        }

        if ($this->unitName->pluck('fixed_pieces')->first() === 1) {

            $this->unitString = "Pieces";
            $this->loadPrice();
        }

        $this->inStocks = $this->stocks->where('item_id', (int)$this->item_id)->where('branch_id', (int)$this->supplier_id)->sum('quantity');

    }

    public function updatedUnitId()
    {

        if ($this->unit_id === "None") {
            $this->reset([
                'quantity',
                'unitPrice',
                'total_amount',
            ]);
            // return;
        }

        $explodeResult = explode(' ', $this->unit_id);
        $unitId = (int) $explodeResult[0];

        $this->unitString = $explodeResult[1];

        if($this->userBranch != 1) {

            $this->unitPriceID = $this->itemPrice->map->only([
                'item_id',
                'price_perUnit',
                'price_perPieces'
                ])
                ->where('item_id', '=', $unitId)
                ->sortBy('created_at')
                ->first();

            $this->unitPrice = $this->unitPriceID['price_perPieces'];

        } else {
            $this->unitPriceID = $this->itemPrice->map->only([
                'item_id',
                'price_perUnit',
                'price_perPieces'
                ])
                ->where('item_id', '=', $unitId)
                ->where('supplier_id', $this->supplier_id)
                ->first();

            $this->unitPrice = $this->unitPriceID['price_perPieces'];
        }

        if ($this->unitString === "Unit") {
            $this->unitPrice = $this->unitPriceID['price_perUnit'];
        }

        if ($this->quantity > 0) {
            $this->computeTotalAmount();
        }


    }

    public function loadPrice()
    {
        if($this->userBranch != 1) {
            $unitPrice = $this->itemPrice->map->only([
                'item_id',
                'price_perUnit',])
                ->where('item_id', '=', (int) $this->item_id)
                ->min('price_perUnit');

            $this->unitPrice = $unitPrice;

        } else {
            $unitPrice = $this->itemPrice->map->only([
                'item_id',
                'price_perUnit',])
                ->where('item_id', '=', (int) $this->item_id)
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
            if(Auth()->user()->role === 'admin') {
                $this->selectedOrders =
                $this->orders
                    ->where('order_status', '!=', 'received')
                    ->where('order_completed', '!=', '1')
                    ->where('deleted_at', null)
                    ->pluck('id');
            } else {
                $this->selectedOrders =
                $this->orders
                    ->where('order_status', '!=', 'received')
                    ->where('order_completed', '=', '1')
                    ->where('branch_id', Auth()->user()->branch_id)
                    ->pluck('id');
            }
        } else {
            $this->selectedOrders = [];
        }
    }

    public function batchcomplete()
    {

        $auth_branch = Auth()->user()->branch_id;
        $item_ids = OrderDetail::whereIn('order_id', $this->selectedOrders)
        ->where('order_status', 'pending')
        ->pluck('item_id')
        ->toArray();

        $sup_ids = OrderDetail::whereIn('order_id', $this->selectedOrders)
        ->where('order_status', 'pending')
        ->pluck('supplier_id')
        ->first();

        $stocks = Stock::whereIn('item_id', $item_ids)
        ->where('branch_id', $sup_ids)
        ->pluck('quantity');

        $orderItems = OrderDetail::whereIn('order_id', $this->selectedOrders)
        ->where('order_status', 'pending')
        ->get();

        $branch_id = Order::whereIn('id', $this->selectedOrders)
        ->pluck('branch_id')
        ->first();

        $hasInventory = $this->branches->where('id', $branch_id)->pluck('has_inventory')->first();

        if ($hasInventory != 1) {
            if($stocks->count() < 1) {
                $notificationMessage = 'No Existing or Insufficient Stock';

                $this->dispatchBrowserEvent('show-message', [
                    'notificationType' => 'error',
                    'messagePrimary'   => $notificationMessage
                ]);
            } else {

                $completedOrderDetailId = [];
                $incompleteOrderId = [];

                foreach ($orderItems as $orderItem) {

                    $orderType = $orderItem->order_type;

                    $itemPieces = Item::where('id', $orderItem->item_id)
                        ->pluck('pieces_perUnit')
                        ->first();

                    (int) $itemQty = (($orderType === 'Unit') ? $itemPieces : 1) * (int) $orderItem->quantity;

                    $stockItems = Stock::where('item_id', $orderItem->item_id)
                        ->where('quantity', '>', 0)
                        ->where('branch_id', $auth_branch)
                        ->orderBy('created_at')
                        ->get();

                    if($orderItem->quantity > $stockItems->sum('quantity')) {
                        array_push($incompleteOrderId, $orderItem->order_id);
                        continue;

                    } else {

                        array_push($completedOrderDetailId, $orderItem->item_id);
                    }

                    foreach ($stockItems as $stockItem) {

                        if ($itemQty > (int) $stockItem->quantity) {

                            Stock::where('id', (int) $stockItem->id)->update([
                                 'quantity' => 0,
                                 'qty_out' => $stockItem->qty_out + $itemQty
                             ]);


                        } else {

                            Stock::where('id', $stockItem->id)
                                ->where('branch_id', $auth_branch)
                                ->update([
                                    'quantity' => $stockItem->quantity - $itemQty,
                                    'qty_out' => $stockItem->qty_out + $itemQty
                                ]);

                        }

                        $itemQty -= $stockItem->quantity;

                        if($itemQty <= 0) {
                            break;
                        }
                    }
                }

                $completedOrderCopy = $completedOrderDetailId;

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

                foreach($this->selectedOrders as $selectedOrder) {

                    DB::table('order_details')
                        ->where('order_id', $selectedOrder)
                        ->whereIn('item_id', $completedOrderDetailId)
                        ->update([
                            'order_status' => 'completed'
                        ]);

                }

                $this->reset([
                    'selectedOrders',
                    'selectALLOrders'
                ]);

                $notificationMessage = 'Order Completed.';

                $this->dispatchBrowserEvent('show-message', [
                    'notificationType' => 'success',
                    'messagePrimary'   => $notificationMessage
                ]);

            }
        } else {

            $getid = OrderDetail::whereIn('order_id', $this->selectedOrders)
            ->where('order_status', 'pending')
            ->get()
            ->toArray();


            $getDataArray = collect($getid);

            $getSelectedItem = $getDataArray
                ->pluck('item_id')
                ->first();

            $item_ids = OrderDetail::whereIn('order_id', $this->selectedOrders)->where('order_status', 'pending')->pluck('item_id')->toArray();

            $sup_ids = OrderDetail::whereIn('order_id', $this->selectedOrders)->where('order_status', 'pending')->pluck('supplier_id')->first();
            $stocks = Stock::whereIn('item_id', $item_ids)->where('branch_id', $sup_ids)->where('quantity', '>', 0)->pluck('quantity');
            $arrayID = [4,5,6];

            if($stocks->count() <= 0 && (!in_array($sup_ids, $arrayID))) {
                $notificationMessage = 'No Existing Stocks or Insufficient Stock';

                $this->dispatchBrowserEvent('show-message', [
                    'notificationType' => 'error',
                    'messagePrimary'   => $notificationMessage
                ]);

                return;
            } else {

                $completedOrderDetailId = [];
                $incompleteOrderId = [];

                foreach ($orderItems as $orderItem) {

                    $orderType = $orderItem->order_type;

                    if($getDataArray[0]['supplier_id'] === 1) {

                        $itemPieces = Item::where('id', $orderItem->item_id)
                            ->pluck('pieces_perUnit')
                            ->first();

                        (int) $itemQty = (($orderType === 'Unit') ? $itemPieces : 1) * (int) $orderItem->quantity;


                        $stockItems = Stock::where('item_id', $orderItem->item_id)
                            ->where('quantity', '>', 0)
                            ->where('branch_id', 1)
                            ->orderBy('created_at')
                            ->get();

                        if($orderItem->quantity > $stockItems->sum('quantity')) {

                            array_push($incompleteOrderId, $orderItem->order_id);
                            continue;

                        } else {

                            array_push($completedOrderDetailId, $orderItem->item_id);
                        }

                        foreach ($stockItems as $stockItem) {

                            if ($itemQty > (int) $stockItem->quantity) {

                                Stock::where('id', (int) $stockItem->id)->update([
                                     'quantity' => 0
                                 ]);


                            } else {

                                Stock::where('id', $stockItem->id)->update([
                                    'quantity' => $stockItem->quantity - $itemQty,
                                ]);
                            }

                            $itemQty -= $stockItem->quantity;

                            if($itemQty <= 0) {
                                break;
                            }
                        }
                    }
                }

                $completedOrderCopy = $completedOrderDetailId;

                foreach ($getid as $detail) {
                    if($getDataArray[0]['supplier_id'] === 1) {
                        if(in_array($detail['item_id'], $completedOrderCopy)) {
                            Stock::create([
                                'branch_id' => $branch_id,

                                'order_id' => $detail['order_id'],

                                'item_id' => $detail['item_id'],

                                'category_id' => $detail['item_id'],

                                'quantity' => $orderType === 'Unit' ? $detail['quantity'] * $itemPieces : $detail['quantity'],

                                'price' => $detail['price'],
                            ]);

                            foreach ($completedOrderCopy as $key => $value) {
                                if($value === $detail['item_id']) {
                                    unset($completedOrderCopy[$key]);
                                }
                            }
                        }
                    } else {
                        Stock::create([
                            'branch_id' => $branch_id,

                            'order_id' => $detail['order_id'],

                            'item_id' => $detail['item_id'],

                            'category_id' => $detail['item_id'],

                            'quantity' => $orderType === 'Unit' ? $detail['quantity'] * $itemPieces : $detail['quantity'],

                            'price' => $detail['price'],
                        ]);
                    }
                }

                foreach($this->selectedOrders as $selectedOrder) {
                    if($getDataArray[0]['supplier_id'] === 1) {
                        DB::table('order_details')
                            ->where('order_id', $selectedOrder)
                            ->whereIn('item_id', $completedOrderDetailId)
                            ->update([
                                'order_status' => 'received',
                                'is_received' => 1
                            ]);
                    } else {
                        DB::table('order_details')
                        ->where('order_id', $selectedOrder)
                        ->update([
                            'order_status' => 'received',
                            'is_received' => 1
                        ]);
                    }
                }

                foreach($this->selectedOrders as $selectedOrder) {
                    if($getDataArray[0]['supplier_id'] === 1) {
                        DB::table('orders')
                            ->where('id', $selectedOrder)
                            ->whereNotIn('id', $incompleteOrderId)
                            ->update([
                                'order_status' => 'received',
                                'received_by' => Auth()->user()->branch_id,
                                'order_completed' => 1,
                                'or_date' => Carbon::now()->format('Y-m-d'),
                                'or_number' => $selectedOrder
                            ]);
                    } else {
                        DB::table('orders')
                        ->where('id', $selectedOrder)
                        ->update([
                            'order_status' => 'received',
                            'received_by' => Auth()->user()->branch_id,
                            'order_completed' => 1,
                            'or_date' => Carbon::now()->format('Y-m-d'),
                            'or_number' => $selectedOrder
                        ]);
                    }
                }
            }

            $this->reset([
                'selectedOrders',
                'selectALLOrders'
            ]);

            $notificationMessage = 'Items has been added to Stocks.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        }

    }


    public function resetCache()
    {
        Cache::flush();

        return redirect('/orders/create-order');

    }


    public function batchreceive()
    {


        $item_ids = OrderDetail::whereIn('order_id', $this->selectedOrders)
        ->pluck('item_id')
        ->toArray();

        $sup_ids = OrderDetail::whereIn('order_id', $this->selectedOrders)
        ->pluck('supplier_id')
        ->first();

        $orderItems = OrderDetail::whereIn('order_id', $this->selectedOrders)
        ->where('order_status', 'completed')
        ->get();

        foreach ($orderItems as $orderItem) {

            $orderType = $orderItem->order_type;

            $itemPieces = Item::where('id', $orderItem->item_id)
                ->pluck('pieces_perUnit')
                ->first();

            (int) $itemQty = (($orderType === 'Unit') ? $itemPieces : 1) * (int) $orderItem->quantity;



            $stockItems = Stock::where('item_id', $orderItem->item_id)
            ->where('qty_out', '>', 0)
            ->where('branch_id', $sup_ids)
            ->orderBy('created_at')
            ->get();

            foreach ($stockItems as $stockItem) {

                Stock::where('id', (int) $stockItem->id)->update([
                    'qty_out' => $orderType === 'Pieces' ? $stockItem->qty_out - $orderItem->quantity : $stockItem->qty_out - $itemQty
                ]);

                $itemQty -= $stockItem->qty_out;

                if($itemQty <= 0) {
                    break;
                }

            }
        }

        foreach($this->selectedOrders as $selectedOrder) {
            DB::table('orders')
            ->select('id', 'order_status')
                ->where('id', $selectedOrder)
                ->update([
                    'order_status' => 'received',
                ]);

            DB::table('order_details')
            ->select('order_id', 'order_status')
                ->where('order_id', $selectedOrder)
                ->where('order_status', 'completed')
                ->update([
                    'order_status' => 'received',
                    'is_received' => 1
                ]);
        }


        $notificationMessage = 'Items has been received';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'success',
            'messagePrimary'   => $notificationMessage
        ]);


        $this->reset([
            'selectedOrders',
            'selectALLOrders'
        ]);

    }
}
