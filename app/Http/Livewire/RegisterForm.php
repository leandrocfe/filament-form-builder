<?php

namespace App\Http\Livewire;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
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
            'postal_code' => '01001-000',
            'number' => 11
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Step::make('account')
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
                    ])
                    ->columns(2),
                Step::make('address')
                    ->schema([
                        TextInput::make('postal_code')
                            ->required()
                            ->mask(fn (Mask $mask) => $mask->pattern('00000-000'))
                            ->minLength(8)
                            ->suffixAction(function ($state, $livewire, $set) {
                                return Action::make('search-action')
                                    ->icon('heroicon-o-search')
                                    ->action(function () use ($state, $livewire, $set) {

                                        $livewire->validateOnly('postal_code');

                                        $request = Http::get("viacep.com.br/ws/{$state}/json/")->json();

                                        if (!isset($request['erro'])) {

                                            $set('street', $request['logradouro']);
                                            $set('complement', $request['complemento']);
                                            $set('district', $request['bairro']);
                                            $set('city', $request['localidade']);
                                            $set('state', $request['uf']);
                                        } else {

                                            $set('street', null);
                                            $set('complement', null);
                                            $set('district', null);
                                            $set('city', null);
                                            $set('state', null);

                                            throw ValidationException::withMessages([
                                                'postal_code' => 'Este cep é inválido.'
                                            ]);
                                        }
                                    });
                            })
                            ->columnSpan(2),
                        TextInput::make('street')
                            ->required()
                            ->columnSpan(4),
                        TextInput::make('number')
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('complement')
                            ->required()
                            ->columnSpan(4),
                        TextInput::make('district')
                            ->required()
                            ->columnSpan(3),
                        TextInput::make('city')
                            ->required()
                            ->columnSpan(3),
                        Select::make('state')
                            ->required()
                            ->searchable()
                            ->options(File::json(public_path('data/state.json')))
                            ->columnSpanFull()
                    ])
                    ->columns(6),
            ])
                ->startOnStep(2)
                ->submitAction(new HtmlString(view('livewire.register-form-submit')))
                ->extraAttributes(['class' => 'dark:text-white'])
                ->extraAlpineAttributes(['@form-submitted.window' => 'step = \'account\''])
        ];
    }

    public function submit(): void
    {
        sleep(1);
        $this->data = $this->form->getState();
        $this->dispatchBrowserEvent('open-modal', ['id' => 'registerFormSuccess']);

        $this->form->fill();

        $this->dispatchBrowserEvent('form-submitted');
    }
}
