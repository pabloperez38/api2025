<?php


namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::create([
            'nombre' => 'Electrónica'
        ]);
        Categoria::create([
            'nombre' => 'Ropa'
        ]);
        Categoria::create([
            'nombre' => 'Alimentos'
        ]);
    }
}
