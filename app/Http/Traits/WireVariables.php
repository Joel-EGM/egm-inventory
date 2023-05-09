<?php

namespace App\Http\Traits;

trait WireVariables
{
    //Suppliers Variables
    // ----------------------------------------------------------------
    public $getsuppliers =[];
    public $getsupplierName;
    public $getsupplierEmail;
    public $getsupplierContactNo;
    // ----------------------------------------------------------------

    //Branches Variables
    // ----------------------------------------------------------------
    public $allbranches =[];
    public $branchName;
    public $branchAddress;
    public $branchContactNo;
    // ----------------------------------------------------------------

    //Item Variables
    // ----------------------------------------------------------------
    public $itemName;
    public $unitIName;
    public $suppliers;
    public $piecesPerUnit;
    public $reorder_level;
    public $fixedUnit = false;
    // ----------------------------------------------------------------

    //Items Prices Variables
    // ----------------------------------------------------------------
    public $allitems;
    public $itemprices = [];
    public $priceArrays = [];
    public $unitAName = [];
    public $allsuppliers;
    // ----------------------------------------------------------------

    //Users Variables
    // ----------------------------------------------------------------
    public $allusers =[];
    
    public $userRole;
    public $password;
    public $password_confirmation;
    // ----------------------------------------------------------------

    //Order Variables
    // ----------------------------------------------------------------
    public $orderArrays = [];
    public $itemList = [];
    public $unitName = [];
    public $selectedRecord = [];

    public $order_id;
    public $unit_id;

    public $getBranchID;
    public $getOrderID;

    public $orders;
    public $items;
    public $branches;

    public $branch_name;
    public $branchFind;
    public $order_details;
    public $order_date;
    public $order_status;
    public $details;

    public $quantity;
    public $price;
    public $total_amount;
    public $unitPrice;
    public $unitType;
    public $unitPriceID;

    public $users;

    public $completedOrder = false;
    public $unitString;
    // ----------------------------------------------------------------
}
