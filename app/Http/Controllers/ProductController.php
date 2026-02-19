<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $products = Product::with('user')->paginate(5);
        } else {
            $products = auth()->user()->products()->paginate(5);
        }
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        
        Product::create($validated);
        return redirect()
            ->route('products.index')
            ->with('success', 'Producto creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (!auth()->user()->isAdmin() && $product->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este producto');
        }
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        if (!auth()->user()->isAdmin() && $product->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este producto');
        }
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        if (!auth()->user()->isAdmin() && $product->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para actualizar este producto');
        }
        $product->update($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Producto no encontrado');
        }

        if (!auth()->user()->isAdmin() && $product->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este producto');
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}
