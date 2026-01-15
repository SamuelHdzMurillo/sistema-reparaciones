<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entidad extends Model
{
    use HasFactory;

    protected $table = 'entidades';

    protected $fillable = [
        'nombre',
    ];

    public function bienes(): HasMany
    {
        return $this->hasMany(Bien::class);
    }
}
