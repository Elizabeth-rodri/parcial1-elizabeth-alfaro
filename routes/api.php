<?php

use App\Http\Controllers\Api\catalogos\CategoriasController;
use App\Http\Controllers\auth\AuthenticationController;
use App\Http\Controllers\auth\RolPermissionController;
use App\Http\Controllers\auth\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\catalogos\MarcasController;
use App\Http\Controllers\Api\catalogos\ProveedoresController;
use App\Http\Controllers\ProductoController;

Route::get('/productos', [ProductoController::class, 'apiProductos']);

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/logout',[AuthenticationController::class,'logout'])->middleware('rolePermission:Super Admin,Admin');
    Route::post('/refresh',[AuthenticationController::class,'refresh'])->middleware('rolePermission:Super Admin,Admin');
    Route::post('/validate-token',[AuthenticationController::class,'validatedToken']);
});

/*
|--------------------------------------------------------------------------
| USERS
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->middleware('rolePermission:Super Admin');
    Route::post('/', [UserController::class, 'createUser'])->middleware('rolePermission:Super Admin');
    Route::post('/agregar-permisos/{userId}',[UserController::class,'AgregarPermisoUsuario'])->middleware('rolePermission:Super Admin');
    Route::post('/asignar-rol/{userId}',[UserController::class,'AsignarRolUsuario'])->middleware('rolePermission:Super Admin');
    Route::post('/revocar-rol/{userId}',[UserController::class,'RevocarRolUsuario'])->middleware('rolePermission:Super Admin');
    Route::post('/revocar-permisos/{userId}',[UserController::class,'RevocarPermisoUsuario'])->middleware('rolePermission:Super Admin');
});

/*
|--------------------------------------------------------------------------
| ROLES Y PERMISOS
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->prefix('rol-permisos')->group(function () {
    Route::get('/lista-permisos',[RolPermissionController::class,'ListPermission'])->middleware('rolePermission:Super Admin');
    Route::get('/lista-roles',[RolPermissionController::class,'ListRole'])->middleware('rolePermission:Super Admin');
    Route::post('/create-permission',[RolPermissionController::class,'createPermission'])->middleware('rolePermission:Super Admin');
    Route::post('/create-rol',[RolPermissionController::class,'createRol'])->middleware('rolePermission:Super Admin');
    Route::delete('/eliminar-rol/{id}',[RolPermissionController::class,'eliminarRol'])->middleware('rolePermission:Super Admin');
    Route::delete('/eliminar-permiso',[RolPermissionController::class,'eliminarPermisos'])->middleware('rolePermission:Super Admin');
});

/*
|--------------------------------------------------------------------------
| CATALOGOS
|--------------------------------------------------------------------------
*/
Route::prefix('catalogos')->group(function () {

    // ================= CATEGORIAS =================
    Route::prefix('categorias')->group(function(){
        Route::get('/', [CategoriasController::class,'index'])->middleware('rolePermission:Super Admin,Admin');
        Route::post('/', [CategoriasController::class,'store'])->middleware('rolePermission:Super Admin');
        Route::put('/{idCategoria}', [CategoriasController::class,'updateCategoria'])->middleware('rolePermission:Super Admin');
        Route::delete('/{idCategoria}', [CategoriasController::class,'eliminarCategoria'])->middleware('rolePermission:Super Admin');
    });

    // ================= MARCAS =================
    Route::prefix('marcas')->group(function(){
        Route::get('/', [MarcasController::class,'index'])->middleware('rolePermission:Super Admin,Admin');
        Route::post('/', [MarcasController::class,'store'])->middleware('rolePermission:Super Admin');
        Route::put('/{idmarca}', [MarcasController::class,'updateMarca'])->middleware('rolePermission:Super Admin');
        Route::delete('/{idmarca}', [MarcasController::class,'eliminarMarca'])->middleware('rolePermission:Super Admin');
    });

    // ================= PROVEEDORES =================
    Route::prefix('proveedores')->group(function(){
        Route::get('/', [ProveedoresController::class,'index'])->middleware('rolePermission:Super Admin,Admin');
        Route::post('/', [ProveedoresController::class,'store'])->middleware('rolePermission:Super Admin');
        Route::put('/{idproveedor}', [ProveedoresController::class,'updateProveedor'])->middleware('rolePermission:Super Admin');
        Route::delete('/{idproveedor}', [ProveedoresController::class,'eliminarProveedor'])->middleware('rolePermission:Super Admin');
    });

});