<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'telefono',
    ];

    public function reparaciones(): HasMany
    {
        return $this->hasMany(Reparacion::class);
    }
}
