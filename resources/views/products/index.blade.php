@extends('layouts.app')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Listado de productos</h2>

    <a href="{{ route('products.create') }}"
       class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        + Nuevo producto
    </a>
</div>

@if (session('success'))
    <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white shadow rounded overflow-x-auto">
    <table class="w-full table-auto">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">Nombre</th>
                <th class="p-3 text-left">Descripción</th>
                <th class="p-3 text-left">Precio</th>
                <th class="p-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr class="border-t">
                    <td class="p-3">{{ $product->name }}</td>
                    <td class="p-3">{{ $product->description }}</td>
                    <td class="p-3">${{ number_format($product->price, 2) }}</td>
                    <td class="p-3 text-center space-x-2">

                        <a href="{{ route('products.show', $product) }}"
                           class="bg-blue-500 text-white px-3 py-1 rounded text-sm">
                            Ver
                        </a>

                        <a href="{{ route('products.edit', $product) }}"
                           class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">
                            Editar
                        </a>

                        <form action="{{ route('products.destroy', $product) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">
                                Eliminar
                            </button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">
                        No hay productos registrados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>

@endsection
