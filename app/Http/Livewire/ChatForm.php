<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Chat;

class ChatForm extends Component
{
    public $message;

    public function render()
    {
        return view('livewire.chat-form');
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required',
        ]);

        Chat::create([
            'message' => $this->message,
        ]);

        $this->message = '';
        
        $this->emit('messageSent');
    }
}
