<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class SignUp extends Component
{

    public $email;
    public $password;
    public $password_confirmation;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:200'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
            ]
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function signUp()
    {
        $validated = $this->validate();

        $user = User::create([
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);

        event(new Registered($user));

        Auth::login($user);

        session()->regenerate();


        return redirect(route('dashboard'));
    }

    public function navigateToSignIn()
    {
        $this->redirect(route('sign.in'));
    }

    public function render()
    {
        return view('livewire.auth.sign-up')->layout('components.layouts.auth');
    }
}
