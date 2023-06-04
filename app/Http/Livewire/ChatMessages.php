<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Chat;

class ChatMessages extends Component
{
    public $messages;

    protected $listeners = ['messageSent' => 'refreshMessages'];

    public function mount()
    {
        $this->messages = Chat::all();
    }

    public function render()
    {
        return view('livewire.chat-messages');
    }

    public function refreshMessages()
    {
        $this->messages = Chat::all();
    }
}
