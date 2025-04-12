<?php

namespace App\Livewire;

use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;


class Feedback extends Component
{
    public  $name;
    public  $email;
    public  $phone;
    public  $message;
    public  $services;
    public $showNotification = false;

    protected $rules = [
        'name'     => 'required|min:2|max:50',
        'email'    => 'required|email',
        'phone' => 'required|regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/',
        'message'  => 'required|max:1000',
        'services' => 'required|string',
    ];

    protected $messages = [
        'phone.regex' => 'Некорректный формат телефона.',
        'services.required' => 'Выберите хотя услугу.',
    ];

    public function submit()
    {
        $this->validate();

        $key = 'form-submission:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->dispatch('notification-show', type: 'error', message: 'Слишком много попыток. Попробуйте позже.');
            return response()->json(['error' => 'Слишком много попыток'], 429);
        }

        RateLimiter::hit($key, 3600);

        try {
            \App\Models\Feedback::create([
                'name'     => $this->name,
                'email'    => $this->email,
                'phone'    => $this->phone,
                'message'  => $this->message,
                'services' => $this->services,
            ]);

            $this->reset(['name', 'email', 'phone', 'message', 'services']);
            $this->showNotification = true;

            $this->dispatch('notification-show');
        } catch (\Exception $e) {
            $this->dispatch('notification-show', type: 'error', message: 'Произошла ошибка при отправке заявки. Попробуйте позже.');
        }
    }

    public function render()
    {
        return view('livewire.feedback');
    }
}
