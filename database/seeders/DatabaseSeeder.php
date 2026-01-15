<?php

namespace Database\Seeders;

use App\Models\Bien;
use App\Models\Cliente;
use App\Models\Entidad;
use App\Models\Plantel;
use App\Models\Reparacion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Técnicos
        $tecnico1 = User::create([
            'name' => 'Juan Pérez',
            'numero' => 'TEC-001',
            'email' => 'juan.perez@sistema.com',
            'password' => Hash::make('password123'),
        ]);

        $tecnico2 = User::create([
            'name' => 'María García',
            'numero' => 'TEC-002',
            'email' => 'maria.garcia@sistema.com',
            'password' => Hash::make('password123'),
        ]);

        // Planteles
        $plantel1 = Plantel::create(['nombre' => 'Plantel Central']);
        $plantel2 = Plantel::create(['nombre' => 'Plantel Norte']);
        $plantel3 = Plantel::create(['nombre' => 'Plantel Sur']);

        // Entidades
        $entidad1 = Entidad::create(['nombre' => 'Administración']);
        $entidad2 = Entidad::create(['nombre' => 'Tecnologías de la Información']);
        $entidad3 = Entidad::create(['nombre' => 'Recursos Humanos']);
        $entidad4 = Entidad::create(['nombre' => 'Contabilidad']);

        // Clientes
        $cliente1 = Cliente::create([
            'nombre_completo' => 'Carlos López Mendoza',
            'telefono' => '5551234567',
        ]);

        $cliente2 = Cliente::create([
            'nombre_completo' => 'Ana Martínez Ruiz',
            'telefono' => '5559876543',
        ]);

        $cliente3 = Cliente::create([
            'nombre_completo' => 'Roberto Sánchez Villa',
            'telefono' => '5552468135',
        ]);

        // Bienes
        $bien1 = Bien::create([
            'numero_inventario' => 'INV-2024-001',
            'tipo_bien' => 'Laptop',
            'marca' => 'Dell',
            'modelo' => 'Latitude 5520',
            'numero_serie' => 'DL5520-ABC123',
            'especificaciones' => 'Intel Core i7, 16GB RAM, 512GB SSD',
            'plantel_id' => $plantel1->id,
            'entidad_id' => $entidad2->id,
        ]);

        $bien2 = Bien::create([
            'numero_inventario' => 'INV-2024-002',
            'tipo_bien' => 'Proyector',
            'marca' => 'Epson',
            'modelo' => 'PowerLite X49',
            'numero_serie' => 'EP-X49-DEF456',
            'especificaciones' => '3600 lúmenes, XGA, HDMI',
            'plantel_id' => $plantel2->id,
            'entidad_id' => $entidad1->id,
        ]);

        $bien3 = Bien::create([
            'numero_inventario' => 'INV-2024-003',
            'tipo_bien' => 'Impresora',
            'marca' => 'HP',
            'modelo' => 'LaserJet Pro M404dn',
            'numero_serie' => 'HP-M404-GHI789',
            'especificaciones' => 'Láser monocromática, duplex, red',
            'plantel_id' => $plantel1->id,
            'entidad_id' => $entidad3->id,
        ]);

        $bien4 = Bien::create([
            'numero_inventario' => 'INV-2024-004',
            'tipo_bien' => 'Monitor',
            'marca' => 'LG',
            'modelo' => '27UK650-W',
            'numero_serie' => 'LG-27UK-JKL012',
            'especificaciones' => '27 pulgadas, 4K UHD, HDR',
            'plantel_id' => $plantel3->id,
            'entidad_id' => $entidad4->id,
        ]);

        // Reparaciones
        Reparacion::create([
            'bien_id' => $bien1->id,
            'cliente_id' => $cliente1->id,
            'tecnico_id' => $tecnico1->id,
            'falla_reportada' => 'No enciende, se apaga al iniciar Windows',
            'accesorios_incluidos' => 'Cargador original, mouse inalámbrico',
            'estado' => 'proceso',
        ]);

        Reparacion::create([
            'bien_id' => $bien2->id,
            'cliente_id' => $cliente2->id,
            'tecnico_id' => $tecnico1->id,
            'falla_reportada' => 'Imagen borrosa y parpadea constantemente',
            'accesorios_incluidos' => 'Cable HDMI, control remoto',
            'estado' => 'recibido',
        ]);

        Reparacion::create([
            'bien_id' => $bien3->id,
            'cliente_id' => $cliente3->id,
            'tecnico_id' => $tecnico2->id,
            'falla_reportada' => 'Atasco de papel frecuente, no reconoce tóner',
            'accesorios_incluidos' => 'Cable USB, tóner nuevo',
            'estado' => 'listo',
        ]);

        Reparacion::create([
            'bien_id' => $bien4->id,
            'cliente_id' => $cliente1->id,
            'tecnico_id' => $tecnico2->id,
            'falla_reportada' => 'Líneas verticales en pantalla',
            'accesorios_incluidos' => 'Cable de poder, cable DisplayPort',
            'estado' => 'entregado',
        ]);
    }
}
