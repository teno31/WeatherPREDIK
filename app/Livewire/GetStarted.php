<?php

namespace App\Livewire;

use Livewire\Component;

class GetStarted extends Component
{
    public function navigateToSignIn()
    {
        $this->redirect(route('sign.in'));
    }
    public function render()
    {
        return view('livewire.get-started')->layout('components.layouts.guest');
    }
}
