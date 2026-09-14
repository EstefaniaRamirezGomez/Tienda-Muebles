<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EspecificacionMueble extends Model
{
    protected $table = 'especificaciones_muebles';

    protected $fillable = [
        'producto_id', 'material', 'color', 'ancho_cm', 'alto_cm',
        'profundidad_cm', 'peso_kg', 'requiere_ensamblaje', 'garantia_meses',
    ];

    protected $casts = [
        'requiere_ensamblaje' => 'boolean',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function calcularVolumenEnvio(): float
    {
        return round(
            ($this->ancho_cm / 100) * ($this->alto_cm / 100) * ($this->profundidad_cm / 100),
            3
        );
    }

    public function tiempoEnsamblajeEstimado(): int
    {
        return $this->requiere_ensamblaje ? 30 : 0;
    }
}