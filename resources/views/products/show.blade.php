@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-6">Detalle del producto</h2>

<div class="bg-white shadow rounded p-6 max-w-lg">

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
           class="bg-yellow-500 text-white px-4 py-2 rounded">
            Editar
        </a>

        <a href="{{ route('products.index') }}"
           class="bg-gray-500 text-white px-4 py-2 rounded">
            Volver
        </a>
    </div>

</div>

@endsection
