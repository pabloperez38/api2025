<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use Carbon\Carbon;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        $categorias = Categoria::all();

        if ($categorias->isEmpty()) {
            $this->command->info('No hay categorías. Ejecuta primero CategoriaSeeder.');
            return;
        }

        foreach ($categorias as $categoria) {
            for ($i = 1; $i <= 5; $i++) {
                Producto::create([
                    'nombre' => "Producto $i de {$categoria->nombre}",
                    'descripcion' => "Descripción del producto $i en la categoría {$categoria->nombre}",
                    'stock' => rand(10, 100),
                    'precio' => rand(100, 1000) / 10,
                    'peso' => rand(1, 5) + 0.5,
                    'disponible' => (rand(0, 1) == 1),
                    'fecha_vencimiento' => $categoria->nombre == 'Alimentos'
                        ? Carbon::now()->addMonths(rand(1, 6))
                        : null,
                    'publicado_en' => Carbon::now(),
                    'categoria_id' => $categoria->id
                ]);
            }
        }
    }
}
