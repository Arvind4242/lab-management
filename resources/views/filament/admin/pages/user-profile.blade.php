<x-filament-panels::page>
@php($user = auth()->user())

<div class="grid grid-cols-12 gap-6">

    {{-- LEFT COLUMN --}}
    <div class="col-span-12 lg:col-span-4 space-y-6">

        <x-filament::card>
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 rounded-full bg-primary-600 flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div>
                    <h2 class="text-lg font-semibold">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <span class="mt-1 inline-block text-xs px-2 py-1 rounded bg-success-100 text-success-700">
                        User
                    </span>
                </div>
            </div>
        </x-filament::card>

        <x-filament::card>
            <h3 class="font-semibold mb-3">Contact</h3>

            <div class="text-sm space-y-2">
                <div><strong>Mobile:</strong> {{ $user->mobile ?? '—' }}</div>
                <div><strong>Website:</strong> {{ $user->website ?? '—' }}</div>
                <div><strong>Address:</strong> {{ $user->address ?? '—' }}</div>
            </div>
        </x-filament::card>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-span-12 lg:col-span-8 space-y-6">

        <x-filament::card>
            <h3 class="font-semibold mb-4">Lab Information</h3>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><strong>Lab:</strong> {{ $user->lab?->name ?? '—' }}</div>
                <div><strong>Lab Code:</strong> {{ $user->lab_code ?? '—' }}</div>
                <div><strong>Qualification:</strong> {{ $user->qualification ?? '—' }}</div>
                <div><strong>Reference Lab:</strong> {{ $user->reference_lab ?? '—' }}</div>
            </div>
        </x-filament::card>

        <x-filament::card>
            <h3 class="font-semibold mb-4">Account Settings</h3>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Language</span><br>
                    English
                </div>
                <div>
                    <span class="text-gray-500">Timezone</span><br>
                    Asia/Kolkata
                </div>
                <div>
                    <span class="text-gray-500">Joined</span><br>
                    {{ $user->created_at->format('d M Y') }}
                </div>
            </div>
        </x-filament::card>

        <x-filament::card>
            <div class="flex justify-end gap-3">
                <a href="{{ \App\Filament\Admin\Resources\UserResource::getUrl('edit', ['record' => $user]) }}"
                   class="filament-button filament-button-size-md filament-button-color-primary">
                    Edit Profile
                </a>
            </div>
        </x-filament::card>

    </div>

</div>

</x-filament-panels::page>
