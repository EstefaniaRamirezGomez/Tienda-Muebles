<?php

namespace App\Models;

use App\Models\Concerns\TraduceValores;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use TraduceValores;

    protected $fillable = ['nombre'];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    /**
     * Nombre de la categoría en el idioma actual.
     */
    public function getNombreLocalizadoAttribute(): ?string
    {
        return $this->traducirValor($this->nombre, 'categories');
    }
}
