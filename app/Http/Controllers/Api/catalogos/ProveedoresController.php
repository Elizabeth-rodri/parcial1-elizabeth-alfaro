<?php

namespace App\Http\Controllers\Api\catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProveedoresController extends Controller
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
            $proveedor = Proveedor::orderBy('created_at', 'desc');

            if($request->has('nombre')){
                $proveedor->where('nombre', 'ilike', "%{$request->nombre}%");
            }

            $proveedorP = $proveedor->paginate(10);

            return $this->success('Lista Proveedores', 200, $proveedorP);

        } catch (\Throwable $th) {
            return $this->error('Error al obtener los proveedores');
        }
    }

    public function store(Request $request): JsonResponse{
        try{
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|min:2|max:255|string',
                'telefono' => 'required|string|max:20',
                'correo' => 'required|email'
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'error' => $validator->errors()->first(),
                ], 422);
            }

            DB::beginTransaction();

            $proveedor = Proveedor::create($validator->validated());

            DB::commit();

            return $this->success('Proveedor creado con exito', 200, $proveedor);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error al crear el proveedor: ' . $e->getMessage());
        }
    }

    public function updateProveedor(Request $request, $idproveedor): JsonResponse{
        try{
            $validator = Validator::make($request->all(), [
                'nombre' => 'nullable|min:2|max:255|string',
                'telefono' => 'nullable|string|max:20',
                'correo' => 'nullable|email'
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'error' => $validator->errors()->first(),
                ], 422);
            }

            DB::beginTransaction();

            $proveedor = Proveedor::find($idproveedor);

            if (!$proveedor) {
                return $this->error('Proveedor no encontrado', 404);
            }

            foreach($validator->validated() as $key => $value){
                $proveedor->$key = $value;
            }

            $proveedor->save();

            DB::commit();

            return $this->success('Proveedor actualizado correctamente', 200, $proveedor);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error al actualizar el proveedor: ' . $e->getMessage());
        }
    }

    public function eliminarProveedor($idproveedor): JsonResponse{
        try{
            DB::beginTransaction();

            $proveedor = Proveedor::find($idproveedor);

            if (!$proveedor) {
                return $this->error('Proveedor no encontrado', 404);
            }

            $proveedor->estado = !$proveedor->estado;
            $proveedor->save();

            DB::commit();

            return $this->success('Estado del proveedor actualizado', 200, $proveedor);

        } catch (\Throwable $th){
            DB::rollBack();
            return $this->error('Error al eliminar el proveedor: ' . $th->getMessage());
        }
    }
}