<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use DB;

class WireChart extends Component
{
    use ModalVariables;
    use WireVariables;

    public $layoutTitle = 'Record of Rain Coat Request';
    public $data_requester;

    public function mount(){
        $data_requester = collect(DB::select("CALL generateRainCoatReport()"));
        $this->data_requester = $data_requester->map(function ($data) {
            return[
                'branch_name' => $data->branch_name,
                'month' => $data->month,
                'year' => $data->year,
                'order_status' => $data->order_status,
                'requester' => $data->requester,
            ];
        });
    }

    public function render()
    {

        $groups = Order::with('branches')
        ->select(
            'branch_name',
        )
        ->selectRaw('count(*) as total')
        ->join('branches', 'branches.id', '=', 'orders.branch_id')
        ->groupBy('branch_name')
        ->pluck('total', 'branch_name')
        ->all();

        for ($i = 0; $i <= count($groups); $i++) {
            $colours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
        }

        $charts = new Order();
        $charts->labels = (array_keys($groups));
        $charts->dataset = (array_values($groups));
        $charts->colours = $colours;

        return view('livewire.chart', compact('charts'));
    }

    public function modalToggle($formAction = null)
    {
        if (!$formAction) {
            if ($this->Index === null) {
                $this->formTitle = 'Record of Rain Coat Request';
            }

            $this->isFormOpen = !$this->isFormOpen;
            $this->clearAndResetForm();
        } else {
            $this->isDeleteOpen = !$this->isDeleteOpen;
            $this->clearAndResetDelete();
        }
    }
}
