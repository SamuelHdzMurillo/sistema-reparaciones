<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reparacion extends Model
{
    use HasFactory;

    protected $table = 'reparaciones';

    protected $fillable = [
        'bien_id',
        'cliente_id',
        'tecnico_id',
        'falla_reportada',
        'accesorios_incluidos',
        'estado',
    ];

    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }
}
