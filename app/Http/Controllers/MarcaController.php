<?php

namespace App\Http\Controllers\Api\catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marca;

class MarcasController extends Controller
{
    // Listar todas las marcas
    public function index()
    {
        $marcas = Marca::all();
        return response()->json(['data' => $marcas]);
    }

    // Crear nueva marca
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $marca = Marca::create([
            'nombre' => $request->nombre
        ]);

        // Devuelve el objeto creado dentro de data
        return response()->json(['data' => $marca], 201);
    }

    // Actualizar marca
    public function updateMarca(Request $request, $idmarca)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $marca = Marca::findOrFail($idmarca);
        $marca->nombre = $request->nombre;
        $marca->save();

        return response()->json(['data' => $marca]);
    }

    // Eliminar marca (soft delete)
    public function eliminarMarca($idmarca)
    {
        $marca = Marca::findOrFail($idmarca);
        $marca->delete();
        return response()->json(['message' => 'Marca eliminada correctamente']);
    }
}