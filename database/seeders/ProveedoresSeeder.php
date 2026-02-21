<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('proveedores')->insert([
        [
            'nombre' => 'Proveedor Central',
            'telefono' => '7777-8888',
            'correo' => 'proveedor1@gmail.com'
        ],
        [
            'nombre' => 'Importaciones SV',
            'telefono' => '6666-5555',
            'correo' => 'importaciones@gmail.com'
        ],
        [
            'nombre' => 'Distribuidora Tech',
            'telefono' => '9999-0000',
            'correo' => 'tech@gmail.com'
        ],
        
        ]);
    }
}
