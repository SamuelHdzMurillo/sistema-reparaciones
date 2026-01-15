<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bien extends Model
{
    use HasFactory;

    protected $table = 'bienes';

    protected $fillable = [
        'numero_inventario',
        'tipo_bien',
        'marca',
        'modelo',
        'numero_serie',
        'especificaciones',
        'plantel_id',
        'entidad_id',
    ];

    /**
     * Mutator para guardar la marca en MAYÚSCULAS.
     */
    protected function marca(): Attribute
    {
        return Attribute::make(
            set: fn (string $valor) => strtoupper($valor),
        );
    }

    /**
     * Mutator para guardar el modelo en MAYÚSCULAS.
     */
    protected function modelo(): Attribute
    {
        return Attribute::make(
            set: fn (string $valor) => strtoupper($valor),
        );
    }

    public function plantel(): BelongsTo
    {
        return $this->belongsTo(Plantel::class);
    }

    public function entidad(): BelongsTo
    {
        return $this->belongsTo(Entidad::class);
    }

    public function reparaciones(): HasMany
    {
        return $this->hasMany(Reparacion::class);
    }
}
