<div>

    <x-filament-support::modal id="registerFormSuccess" :darkMode="true">
        <x-slot name="header">
            <span class="dark:text-white">Formulário recebido com sucesso!</span>
        </x-slot>

        @if ($data)
            <p class="dark:text-white">Olá {{ $data['first_name'] }},</p>
        @endif

        <p class="dark:text-white">Obrigado por se registrar em nosso site!</p>

        <x-filament-support::button @click="$dispatch('close-modal', { id: 'registerFormSuccess' })" size="sm"
            type="button" class="my-4">Ok
        </x-filament-support::button>

    </x-filament-support::modal>

    <form wire:submit.prevent="submit">

        {{ $this->form }}

    </form>

    <p class="mt-5 dark:text-white">These input field components is part of a larger, open-source library of Tailwind CSS
        components. Learn
        more by going to the official <a class="text-blue-600 hover:underline"
            href="https://flowbite.com/docs/getting-started/introduction/" target="_blank">Flowbite
            Documentation</a>.
    </p>

</div>
