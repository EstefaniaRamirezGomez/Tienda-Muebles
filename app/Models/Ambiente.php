<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ambiente extends Model
{
    protected $fillable = ['nombre'];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}