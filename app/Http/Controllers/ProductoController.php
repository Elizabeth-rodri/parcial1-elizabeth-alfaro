<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    /**
     * Listar todos los productos
     * GET /api/catalogos/productos
     */
    public function index()
    {
        try {
            $productos = Producto::all();
            return response()->json($productos, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo producto
     * POST /api/catalogos/productos
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255|unique:productos,nombre'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $producto = Producto::create([
                'nombre' => $request->nombre
            ]);

            return response()->json([
                'message' => 'Producto creado exitosamente',
                'data' => $producto
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un producto específico
     * GET /api/catalogos/productos/{id}
     */
    public function show($id)
    {
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            return response()->json($producto, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un producto
     * PUT /api/catalogos/productos/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255|unique:productos,nombre,' . $id
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $producto->update([
                'nombre' => $request->nombre
            ]);

            return response()->json([
                'message' => 'Producto actualizado exitosamente',
                'data' => $producto
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar producto (Soft Delete)
     * DELETE /api/catalogos/productos/{id}
     */
    public function destroy($id)
    {
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $producto->delete();

            return response()->json([
                'message' => 'Producto eliminado exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restaurar producto eliminado (opcional)
     * POST /api/catalogos/productos/{id}/restore
     */
    public function restore($id)
    {
        try {
            $producto = Producto::withTrashed()->find($id);

            if (!$producto) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            if (!$producto->trashed()) {
                return response()->json([
                    'message' => 'El producto no está eliminado'
                ], 400);
            }

            $producto->restore();

            return response()->json([
                'message' => 'Producto restaurado exitosamente',
                'data' => $producto
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al restaurar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar productos eliminados (opcional)
     * GET /api/catalogos/productos/trashed
     */
    public function trashed()
    {
        try {
            $productos = Producto::onlyTrashed()->get();
            return response()->json($productos, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener productos eliminados',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}