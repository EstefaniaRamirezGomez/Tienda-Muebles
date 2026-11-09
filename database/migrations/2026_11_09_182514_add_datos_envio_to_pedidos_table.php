<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('nombre_receptor', 150)->nullable();
            $table->string('direccion_envio', 255)->nullable();
            $table->string('ciudad_envio', 100)->nullable();
            $table->string('telefono_contacto', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_receptor',
                'direccion_envio',
                'ciudad_envio',
                'telefono_contacto',
            ]);
        });
    }
};
