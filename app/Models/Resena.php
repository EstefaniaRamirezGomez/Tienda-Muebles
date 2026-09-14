<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resena extends Model
{
    protected $table = 'resenas';

    protected $fillable = ['user_id', 'producto_id', 'calificacion', 'comentario'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function editar(string $comentario, int $calificacion): void
    {
        $this->update(compact('comentario', 'calificacion'));
    }
}