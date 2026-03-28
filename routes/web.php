<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MarcaController;

Route::resource('proveedores', ProveedorController::class)
    ->parameters(['proveedores' => 'proveedor']);
    
Route::resource('marcas', MarcaController::class)
    ->parameters(['marcas' => 'marca']);
    
Route::resource('productos', ProductoController::class)
    ->parameters(['productos' => 'producto']);
    


Route::get('/', [ProductoController::class, 'dashboard']);
