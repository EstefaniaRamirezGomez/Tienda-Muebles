<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Producto extends Model
{
    protected $fillable = [
        'nombre', 'descripcion', 'imagen', 'precio', 'stock', 'categoria_id', 'ambiente_id',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function ambiente(): BelongsTo
    {
        return $this->belongsTo(Ambiente::class);
    }

    public function especificacion(): HasOne
    {
        return $this->hasOne(EspecificacionMueble::class);
    }

    public function detallePedidos(): HasMany
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function resenas(): HasMany
    {
        return $this->hasMany(Resena::class);
    }

    public function actualizarStock(int $cantidad): void
    {
        $this->decrement('stock', $cantidad);
    }

    public function estaDisponible(): bool
    {
        return $this->stock > 0;
    }

    public function calificacionPromedio(): float
    {
        return round($this->resenas()->avg('calificacion') ?? 0, 1);
    }
}