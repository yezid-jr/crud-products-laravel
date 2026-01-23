@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-6">Editar producto</h2>

@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('products.update', $product) }}" method="POST"
      class="bg-white p-6 rounded shadow max-w-lg">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block font-semibold mb-1">Nombre</label>
        <input type="text" name="name"
               value="{{ old('name', $product->name) }}"
               class="w-full border rounded px-3 py-2">
    </div>

    <div class="mb-4">
        <label class="block font-semibold mb-1">Descripción</label>
        <textarea name="description"
                  class="w-full border rounded px-3 py-2"
                  rows="3">{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="mb-4">
        <label class="block font-semibold mb-1">Precio</label>
        <input type="number" step="0.01" name="price"
               value="{{ old('price', $product->price) }}"
               class="w-full border rounded px-3 py-2">
    </div>

    <div class="flex gap-2">
        <button class="bg-yellow-600 text-white px-4 py-2 rounded">
            Actualizar
        </button>

        <a href="{{ route('products.index') }}"
           class="bg-gray-500 text-white px-4 py-2 rounded">
            Cancelar
        </a>
    </div>

</form>

@endsection
