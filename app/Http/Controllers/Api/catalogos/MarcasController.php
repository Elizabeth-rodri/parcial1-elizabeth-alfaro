<?php

namespace App\Http\Controllers\Api\catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marca;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\DB;

class MarcasController extends Controller
{
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

    public function index(Request $request): JsonResponse{
        try {
            $marca = Marca::orderBy('created_at', 'desc');

            if($request->has('nombre')){
                $marca->where('nombre', 'ilike', "%{$request->nombre}%");
            }

            $marcaP = $marca->paginate(10);

            return $this->success('Lista Marcas', 200, $marcaP);

        } catch (\Throwable $th) {
            return $this->error('Error al obtener las marcas');
        }
    }

    public function store(Request $request): JsonResponse{
        try{
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|min:2|max:255|string',
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'error' => $validator->errors()->first(),
                ], 422);
            }

            DB::beginTransaction();

            $marca = Marca::create($validator->validated());

            DB::commit();

            return $this->success('Marca creada con exito', 200, $marca);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error al crear la marca: ' . $e->getMessage());
        }
    }

    public function updateMarca(Request $request, $idmarca): JsonResponse{
        try{
            $validator = Validator::make($request->all(), [
                'nombre' => 'nullable|min:2|max:255|string',
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'error' => $validator->errors()->first(),
                ], 422);
            }

            DB::beginTransaction();

            $marca = Marca::find($idmarca);

            if (!$marca) {
                return $this->error('Marca no encontrada', 404);
            }

            if($request->has('nombre')){
                $marca->nombre = $validator->validated()['nombre'];
            }

            $marca->save();

            DB::commit();

            return $this->success('Marca actualizada correctamente', 200, $marca);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error al actualizar la marca: ' . $e->getMessage());
        }
    }

    public function eliminarMarca($idmarca): JsonResponse{
        try{
            DB::beginTransaction();

            $marca = Marca::find($idmarca);

            if (!$marca) {
                return $this->error('Marca no encontrada', 404);
            }

            $marca->estado = !$marca->estado;
            $marca->save();

            DB::commit();

            return $this->success('Estado de la marca actualizado', 200, $marca);

        } catch (\Throwable $th){
            DB::rollBack();
            return $this->error('Error al eliminar la marca: ' . $th->getMessage());
        }
    }
}
