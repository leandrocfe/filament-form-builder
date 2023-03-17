<div>
    <form wire:submit.prevent="submit">

        {{ $this->form }}

        <x-filament-support::button type="submit" wire:target='submit' class="my-4">Submit
        </x-filament-support::button>
    </form>

    <p class="mt-5 dark:text-white">These input field components is part of a larger, open-source library of Tailwind CSS
        components. Learn
        more by going to the official <a class="text-blue-600 hover:underline"
            href="https://flowbite.com/docs/getting-started/introduction/" target="_blank">Flowbite
            Documentation</a>.
    </p>

    @if ($data)
        <div>
            {{ var_export($data) }}
        </div>
    @endif

</div>
