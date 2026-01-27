<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Nombre</label>
                        <input type="text" name="name"
                               value="{{ old('name') }}"
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Descripción</label>
                        <textarea name="description"
                                  class="w-full border rounded px-3 py-2"
                                  rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Precio</label>
                        <input type="number" step="0.01" name="price"
                               value="{{ old('price') }}"
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="flex gap-2">
                        <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Guardar
                        </button>

                        <a href="{{ route('products.index') }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancelar
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
