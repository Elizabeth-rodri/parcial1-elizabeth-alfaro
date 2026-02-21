<?php

namespace App\Http\Controllers\Api\catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CtlCategoria; // Cambiado a CtlCategoria
use Illuminate\Support\Facades\Validator; // Cambiado de Nette\Utils\Validators
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\DB;

class CategoriasController extends Controller
{
    // Agregamos los métodos success y error dentro del controlador
    protected function success($message, $status = 200, $data = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    protected function error($message, $status = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $status);
    }

    //
    public function index(Request $request): JsonResponse{
        try {
            // Corregido: CtlCategroia -> CtlCategoria
            $categoria = CtlCategoria::orderBy('created_at', 'desc');
            if($request->has('nombre')){
                $categoria->where('nombre', 'ilike', "%{$request->nombre}%");
            }
            if($request->has('estado')){
                $categoria->where('estado', $request->estado);
            }

            $categoriaP = $categoria->paginate(10);
            $paginate = [
                'perPage' => $categoriaP->perPage(),
                'currentPage' => $categoriaP->currentPage(),
                'lastPage' => $categoriaP->lastPage()
            ];
            
            $categoriaMap = $categoriaP->map(function($q) {
                return [
                    'id' => $q->id,
                    'nombre' => $q->nombre,
                ];
            });
            
            return $this->success('Lista Categorias', 200, $categoriaP);
            
        } catch (\Throwable $th) {
            return $this->error('Error al obtener las categorias');
        }
    }
    
    //Método POST
    public function store(Request $request): JsonResponse{ // Cambiado void a JsonResponse
        try{
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|min:2|max:255|string',
                'color' => 'required|string|min:4|max:9', // Corregido max;9 -> max:9
                'icono' => 'required|string|min:2|max:255' 
            ], [
                'nombre.required' => 'El nombre es requerido',
                'nombre.string' => 'El nombre debe ser un texto',
                'nombre.min' => 'El nombre debe tener por lo menos dos caracteres',
                'nombre.max' => 'El nombre debe tener maximo 255 caracteres',

                'color.required' => 'El color es requerido',
                'color.string' => 'El color debe ser un texto',
                'color.min' => 'El color debe tener por lo menos 4 caracteres',
                'color.max' => 'El color debe tener maximo 9 caracteres',

                'icono.required' => 'El icono es requerido',
                'icono.string' => 'El icono debe ser un texto',
                'icono.min' => 'El icono debe tener por lo menos dos caracteres',
                'icono.max' => 'El icono debe tener maximo 255 caracteres',

            ]);
            
            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'error' => $validator->errors()->first(),
                ], 422); // Cambiado 500 a 422 para errores de validación
            }
            
            $validated = $validator->validated();
            
            DB::beginTransaction();
            
            // Corregido: CtlCategoria en lugar de CtlCategroia
            $categoria = CtlCategoria::create($validated);
            
            DB::commit();
            
            return $this->success('Creado con exito', 200, $categoria); // Agregado $categoria
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error al crear la categoria: ' . $e->getMessage());
        }
    }

    public function updateCategoria(Request $request, $idcategoria): JsonResponse{
        try{
            $validator = Validator::make($request->all(), [
                'nombre' => 'nullable|min:2|max:255|string',
                'color' => 'nullable|string|min:4|max:9', // Corregido max;9 -> max:9
                'icono' => 'nullable|string|min:2|max:255' 
            ], [
                'nombre.string' => 'El nombre debe ser un texto',
                'nombre.min' => 'El nombre debe tener por lo menos dos caracteres',
                'nombre.max' => 'El nombre debe tener maximo 255 caracteres',

                'color.string' => 'El color debe ser un texto',
                'color.min' => 'El color debe tener por lo menos 4 caracteres',
                'color.max' => 'El color debe tener maximo 9 caracteres',

                'icono.string' => 'El icono debe ser un texto',
                'icono.min' => 'El icono debe tener por lo menos dos caracteres',
                'icono.max' => 'El icono debe tener maximo 255 caracteres',

            ]);
            
            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'error' => $validator->errors()->first(),
                ], 422);
            }
            
            $validated = $validator->validated();
            
            DB::beginTransaction();

            // Buscar la categoría
            $categoria = CtlCategoria::find($idcategoria);
            
            if (!$categoria) {
                return $this->error('Categoria no encontrada', 404);
            }
            
            // Actualizar campos si vienen en la petición
            if($request->has('nombre')){
                $categoria->nombre = $validated['nombre'];
            }
            if($request->has('color')){
                $categoria->color = $validated['color'];
            }
            if($request->has('icono')){
                $categoria->icono = $validated['icono'];
            }
            
            $categoria->save();

            DB::commit();
            
            return $this->success('Categoria actualizada correctamente', 200, $categoria);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error al actualizar la categoria: ' . $e->getMessage());
        }   
    }

    public function eliminarCategoria($idcategoria): JsonResponse{
        try{
            DB::beginTransaction();
            
            $categoria = CtlCategoria::find($idcategoria);
            
            if (!$categoria) {
                return $this->error('Categoria no encontrada', 404);
            }
            
            $categoria->estado = !$categoria->estado;
            $categoria->save();
            
            DB::commit();
            
            return $this->success('Cambiaste de estado la categoria', 200, $categoria);
            
        } catch (\Throwable $th){
            DB::rollBack();
            return $this->error('Error al eliminar la categoria: ' . $th->getMessage());
        }
    }
}