<x-app-layout>
    <x-slot name="header">Profil Saya</x-slot>

    <div class="max-w-2xl space-y-4">
        <x-ui.card class="p-5">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </x-ui.card>

        <x-ui.card class="p-5">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </x-ui.card>

        <x-ui.card class="p-5">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </x-ui.card>
    </div>
</x-app-layout>
