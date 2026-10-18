<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-tight">
            Pengaturan Akun & Profil
        </h2>
        <p class="text-xs text-slate-400 mt-1">
            Kelola data pribadi, informasi kontak, dan keamanan kata sandi akun Anda
        </p>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="p-6 sm:p-8 bg-slate-800/80 border border-slate-700/80 rounded-2xl shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-slate-800/80 border border-slate-700/80 rounded-2xl shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-slate-800/80 border border-slate-700/80 rounded-2xl shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
