<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $fillable = [
        'user_id',
        'fecha',
        'estado',
        'total',
        'nombre_receptor',
        'direccion_envio',
        'ciudad_envio',
        'telefono_contacto',
    ];

    protected $casts = [
        'fecha' => 'date',
        'total' => 'decimal:2',
    ];

    /**
     * Usuario que realizó el pedido.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Productos incluidos en el pedido.
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePedido::class);
    }

    /**
     * Calcular el total del pedido.
     */
    public function calcularTotal(): float
    {
        return (float) $this->detalles->sum(
            fn ($detalle) => $detalle->subtotal()
        );
    }

    /**
     * Cancelar el pedido.
     *
     * Nota: este método únicamente cambia el estado.
     * La devolución del inventario debe implementarse
     * antes de habilitar cancelaciones.
     */
    public function cancelar(): void
    {
        $this->update([
            'estado' => 'cancelado',
        ]);
    }

    /**
     * Actualizar el estado del pedido.
     */
    public function actualizarEstado(string $estado): void
    {
        $this->update([
            'estado' => $estado,
        ]);
    }
}