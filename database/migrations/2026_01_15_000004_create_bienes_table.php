<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bienes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_inventario')->unique();
            $table->string('tipo_bien');
            $table->string('marca');
            $table->string('modelo');
            $table->string('numero_serie')->nullable();
            $table->text('especificaciones')->nullable();
            $table->foreignId('plantel_id')->constrained('planteles')->onDelete('cascade');
            $table->foreignId('entidad_id')->constrained('entidades')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bienes');
    }
};
