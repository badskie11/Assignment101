<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ItemModal extends Component
{
    public $item;
    public $isOpen = false;

    public function mount($item)
    {
        $this->item = $item;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function save()
    {
        // Save changes to the item
        $this->item->save();

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.item-modal');
    }
}
