<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedidos';

    protected $fillable = ['pedido_id', 'producto_id', 'cantidad', 'precio_unitario'];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function subtotal(): float
    {
        return round($this->cantidad * (float) $this->precio_unitario, 2);
    }
}