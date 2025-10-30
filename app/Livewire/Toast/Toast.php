<?php

namespace App\Livewire\Toast;

use Livewire\Attributes\On;
use Livewire\Component;

class Toast extends Component
{

    public $open;
    public $message;

    public function mount()
    {
        $this->open = false;
    }

    #[On('showToast')]
    public function showToast($message = null)
    {
       $this->open = true; 
       $this->message = $message;
    }

    public function render()
    {
        return view('livewire.toast.toast');
    }
}
