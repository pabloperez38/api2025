<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->integer('stock'); // números enteros
            $table->decimal('precio', 10, 2); // decimales con 2 lugares
            $table->float('peso')->nullable(); // valores decimales flotantes           
            // Datos booleanos
            $table->boolean('disponible')->default(true);
            $table->date('fecha_vencimiento')->nullable();
            $table->timestamp('publicado_en')->nullable();
            // Clave foránea a Categoría
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade'); // si se borra la categoría, se borran sus productos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
