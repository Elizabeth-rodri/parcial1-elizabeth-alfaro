<?php

namespace App\Http\Controllers\Api\catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marca;

class MarcasController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Marca::all()]);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        $marca = Marca::create(['nombre' => $request->nombre]);
        return response()->json(['data' => $marca], 201);
    }

    public function updateMarca(Request $request, $idmarca)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        $marca = Marca::findOrFail($idmarca);
        $marca->nombre = $request->nombre;
        $marca->save();
        return response()->json(['data' => $marca]);
    }

    public function eliminarMarca($idmarca)
    {
        $marca = Marca::findOrFail($idmarca);
        $marca->delete();
        return response()->json(['message' => 'Marca eliminada correctamente']);
    }
}