<?php

namespace App\Http\Livewire;

use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Livewire\Component;

class RegisterForm extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public $data = null;

    public function render()
    {
        return view('livewire.register-form');
    }

    public function mount(): void
    {
        $this->form->fill([
            'email' => fake()->email(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'company' => fake()->company(),
            'phone_number' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'unique_visitors' => fake()->numberBetween(1, 100),
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => true,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [

            Grid::make(2)
                ->schema([

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->columnSpanFull(),

                    TextInput::make('first_name')
                        ->rule('min:3')
                        ->required()
                        ->validationAttribute('Nome'),

                    TextInput::make('last_name')
                        ->required(),

                    TextInput::make('company')
                        ->required(),

                    TextInput::make('phone_number')
                        ->required(),

                    TextInput::make('website')
                        ->url()
                        ->required(),

                    TextInput::make('unique_visitors')
                        ->numeric()
                        ->required(),

                    TextInput::make('password')
                        ->password()
                        ->required()
                        ->confirmed(),

                    TextInput::make('password_confirmation')
                        ->password()
                        ->required(),

                    Checkbox::make('terms')
                        ->label('I agree with the terms and conditions.')
                        ->columnSpanFull(),
                ]),
        ];
    }

    public function submit(): void
    {
        sleep(1);
        $this->data = $this->form->getState();
    }
}
