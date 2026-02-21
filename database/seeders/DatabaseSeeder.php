<?php

namespace Database\Seeders;


use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\MarcasSeeder;
use Database\Seeders\CategoriasSeeder;
use Database\Seeders\ProveedoresSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            MarcasSeeder::class,
            CategoriasSeeder::class,
            ProveedoresSeeder::class,
         
        ]);
    }
}
