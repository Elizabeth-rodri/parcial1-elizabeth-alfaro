<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;


class ProveedorController extends Controller
{
    public function index()
    {
        return Proveedor::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email'
        ]);

        $proveedor = Proveedor::create($request->all());

        return response()->json($proveedor, 201);
    }

    public function show(Proveedor $proveedor)
    {
        return $proveedor;
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email'
        ]);

        $proveedor->update($request->all());

        return response()->json($proveedor, 200);
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return response()->json(['message' => 'Proveedor eliminado'], 200);
    }
}