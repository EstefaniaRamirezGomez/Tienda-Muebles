<?php

// Autor: Equipo Muebles & Decoración

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('certificado_serial', 60)->nullable()->after('telefono_contacto');
            $table->string('certificado_hash', 64)->nullable()->unique()->after('certificado_serial');
            $table->unsignedInteger('certificado_bloque')->nullable()->after('certificado_hash');
            $table->timestamp('certificado_emitido_en')->nullable()->after('certificado_bloque');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropUnique(['certificado_hash']);
            $table->dropColumn([
                'certificado_serial',
                'certificado_hash',
                'certificado_bloque',
                'certificado_emitido_en',
            ]);
        });
    }
};
