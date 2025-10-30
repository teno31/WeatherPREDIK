<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SignIn extends Component
{

    public $email;
    public $password;

    public function navigateToSignUp()
    {
        $this->redirect(route('sign.up'));
    }

    public function signIn()
    {

        $validatedCredentials = $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt($validatedCredentials)) {
            session()->regenerate();
            return redirect(route('dashboard'));
        }

    }
    
    public function render()
    {
        return view('livewire.auth.sign-in')->layout('components.layouts.auth');
    }
}
