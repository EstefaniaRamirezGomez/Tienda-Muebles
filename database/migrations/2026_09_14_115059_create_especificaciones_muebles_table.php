<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('especificaciones_muebles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->unique()->constrained('productos')->cascadeOnDelete();
            $table->string('material', 60);
            $table->string('color', 60);
            $table->decimal('ancho_cm', 6, 1);
            $table->decimal('alto_cm', 6, 1);
            $table->decimal('profundidad_cm', 6, 1);
            $table->decimal('peso_kg', 6, 2);
            $table->boolean('requiere_ensamblaje')->default(false);
            $table->unsignedSmallInteger('garantia_meses')->default(12);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('especificaciones_muebles');
    }
};