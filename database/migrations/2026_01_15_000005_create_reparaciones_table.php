<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reparaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bien_id')->constrained('bienes')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('tecnico_id')->constrained('users')->onDelete('cascade');
            $table->text('falla_reportada');
            $table->text('accesorios_incluidos')->nullable();
            $table->enum('estado', ['recibido', 'proceso', 'listo', 'entregado'])->default('recibido');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reparaciones');
    }
};
