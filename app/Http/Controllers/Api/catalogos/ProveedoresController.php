<?php

namespace App\Http\Controllers\Api\catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedoresController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Proveedor::all()]);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        $proveedor = Proveedor::create(['nombre' => $request->nombre]);
        return response()->json(['data' => $proveedor], 201);
    }

    public function updateProveedor(Request $request, $idproveedor)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        $proveedor = Proveedor::findOrFail($idproveedor);
        $proveedor->nombre = $request->nombre;
        $proveedor->save();
        return response()->json(['data' => $proveedor]);
    }

    public function eliminarProveedor($idproveedor)
    {
        $proveedor = Proveedor::findOrFail($idproveedor);
        $proveedor->delete();
        return response()->json(['message' => 'Proveedor eliminado correctamente']);
    }
}