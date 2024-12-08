<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                @if(Auth::check()) {{-- Ensure the user is logged in --}}
                <h1>Bienvenue à l'hôpital {{ Auth::user()->name }}</h1>
                <p>Adresse : {{ Auth::user()->address }}</p>
                <p>Description : {{ Auth::user()->description }}</p>
                @else
                <p>Vous n'êtes pas autorisé à accéder à cette page.</p>
                @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
