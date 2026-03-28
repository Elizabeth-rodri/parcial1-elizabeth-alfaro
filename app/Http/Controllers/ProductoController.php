<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function apiProductos()
    {
        return response()->json(['data' => Producto::all()]);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        $producto = Producto::create(['nombre' => $request->nombre]);
        return response()->json(['data' => $producto], 201);
    }

    public function updateProducto(Request $request, $id)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        $producto = Producto::findOrFail($id);
        $producto->nombre = $request->nombre;
        $producto->save();
        return response()->json(['data' => $producto]);
    }

    public function eliminarProducto($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return response()->json(['message' => 'Producto eliminado correctamente']);
    }
}