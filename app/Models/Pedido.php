<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $fillable = ['user_id', 'fecha', 'estado', 'total'];

    protected $casts = [
        'fecha' => 'date',
        'total' => 'decimal:2',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function calcularTotal(): float
    {
        return (float) $this->detalles->sum(fn ($d) => $d->subtotal());
    }

    public function cancelar(): void
    {
        $this->update(['estado' => 'cancelado']);
    }

    public function actualizarEstado(string $estado): void
    {
        $this->update(['estado' => $estado]);
    }
}