<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="mb-4">
                    <span class="font-semibold">Nombre:</span>
                    <p class="text-gray-700">{{ $product->name }}</p>
                </div>

                <div class="mb-4">
                    <span class="font-semibold">Descripción:</span>
                    <p class="text-gray-700">{{ $product->description }}</p>
                </div>

                <div class="mb-4">
                    <span class="font-semibold">Precio:</span>
                    <p class="text-gray-700">
                        ${{ number_format($product->price, 2) }}
                    </p>
                </div>

                <div class="flex gap-2 mt-6">
                    <a href="{{ route('products.edit', $product) }}"
                       class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Editar
                    </a>

                    <a href="{{ route('products.index') }}"
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Volver
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
