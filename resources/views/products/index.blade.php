<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold">Lista de Productos</h1>
                    <a href="{{ route('products.create') }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Nuevo Producto
                    </a>
                </div>

                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="p-3 text-left">Nombre</th>
                            <th class="p-3 text-left">Descripción</th>
                            <th class="p-3 text-left">Precio</th>
                            @if(auth()->user()->isAdmin())
                                <th class="p-3 text-left">Propietario</th>
                            @endif
                            <th class="p-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3">{{ $product->name }}</td>
                                <td class="p-3">{{ $product->description }}</td>
                                <td class="p-3">${{ number_format($product->price, 2) }}</td>
                                @if(auth()->user()->isAdmin())
                                    <td class="p-3">{{ $product->user->name ?? 'Sin asignar' }}</td>
                                @endif
                                <td class="p-3 text-center space-x-2">
                                    <a href="{{ route('products.show', $product) }}"
                                       class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm inline-block">
                                        Ver
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="bg-yellow-500 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm inline-block">
                                        Editar
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('¿Estás seguro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-3 text-center text-gray-500">
                                    No hay productos disponibles
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
