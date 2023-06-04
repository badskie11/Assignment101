<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DataPoint;

class RealTimeChart extends Component
{
    public $dataPoints = [];

    protected $listeners = ['dataPointAdded' => 'getDataPoints'];

    public function render()
    {
        return view('livewire.real-time-chart');
    }

    public function getDataPoints()
    {
        $this->dataPoints = DataPoint::pluck('value')->toArray();
        $this->emit('chartUpdated');
    }
}
