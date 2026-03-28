<?php

namespace App\Http\Controllers\Api\catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedoresController extends Controller
{
    // Listar proveedores
    public function index()
    {
        $proveedores = Proveedor::all();
        return response()->json(['data' => $proveedores]);
    }

    // Crear proveedor
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $proveedor = Proveedor::create([
            'nombre' => $request->nombre
        ]);

        return response()->json(['data' => $proveedor], 201);
    }

    // Actualizar proveedor
    public function updateProveedor(Request $request, $idproveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $proveedor = Proveedor::findOrFail($idproveedor);
        $proveedor->nombre = $request->nombre;
        $proveedor->save();

        return response()->json(['data' => $proveedor]);
    }

    // Eliminar proveedor
    public function eliminarProveedor($idproveedor)
    {
        $proveedor = Proveedor::findOrFail($idproveedor);
        $proveedor->delete();
        return response()->json(['message' => 'Proveedor eliminado correctamente']);
    }
}