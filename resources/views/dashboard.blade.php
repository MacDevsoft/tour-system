<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
    
   <h2 class="text-2xl font-bold mb-4">
    Bienvenido {{ auth()->user()->name }}
</h2>

@if(auth()->user()->role === 'admin')
    <div class="p-6 bg-green-200 rounded space-y-4">
        <p>👑 Eres ADMIN - aquí irá tu panel de control</p>

        <a href="/tours" class="bg-blue-600 !text-white px-4 py-2 rounded shadow inline-block">
            Ver / Editar Tours
        </a>
    </div>
@else
    <div class="p-6 bg-blue-200 rounded">
        👤 Eres USUARIO - aquí verás tus tours
    </div>
@endif

</div>
            </div>
        </div>
    </div>
</x-app-layout>
