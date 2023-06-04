<?php

namespace App\Http\Livewire;

use App\Models\ListItem;
use Livewire\Component;
use Livewire\WithPagination;

class ReorderList extends Component
{
    use WithPagination;

    public $Resident;

    public function mount()
    {
        $this->Resident = Resident::orderBy('order')->get();
    }

    public function render()
    {
        return view('livewire.reorder-list');
    }

    public function dragDrop($list)
    {
        foreach ($list as $index => $item) {
            Resident::where('FirstName', $item['value'])->update(['order' => $index]);
        }

        $this->Residents = Resident::orderBy('order')->get();
    }
}
