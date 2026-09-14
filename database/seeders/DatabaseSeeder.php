<?php

namespace Database\Seeders;

use App\Models\Ambiente;
use App\Models\Categoria;
use App\Models\DetallePedido;
use App\Models\EspecificacionMueble;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Resena;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = User::create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@ejemplo.com',
            'password' => bcrypt('password'),
            'rol' => 'cliente',
        ]);

        User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@ejemplo.com',
            'password' => bcrypt('password'),
            'rol' => 'admin',
        ]);

        $categorias = collect(['Sofás', 'Mesas', 'Sillas', 'Lámparas'])
            ->mapWithKeys(fn ($nombre) => [$nombre => Categoria::create(['nombre' => $nombre])]);

        $ambientes = collect(['Sala', 'Dormitorio', 'Cocina', 'Oficina', 'Exterior'])
            ->mapWithKeys(fn ($nombre) => [$nombre => Ambiente::create(['nombre' => $nombre])]);

        $datos = [
            ['Sofá Nórdico 3 puestos', 'Sofás', 'Sala', 1890000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRqAt3inXQXUeJl7YAIa4Yiqh8ry6FMr7gKlQbF3IsnFQ&s=10','Tela', 'Gris', 210, 85, 90, 45, true, 24],
            ['Sofá Chaise Longue', 'Sofás', 'Sala', 2350000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSh6oVsuuhnxwhbYiPzNbWH_mYMbfNPVxbetuW-l_liUA&s=10', 'Cuero sintético', 'Café', 260, 80, 160, 60, true, 24],
            ['Mesa de Centro Roble', 'Mesas', 'Sala', 680000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRA1Z322ekhz_lCiMm4hb366PyjEdR6WPRoTKYQoIA2Rg&s=10', 'Madera de roble', 'Natural', 110, 45, 60, 18, false, 12],
            ['Mesa Comedor Extensible', 'Mesas', 'Cocina', 1450000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQWuzmwK5zEvbbF5O3_t7u5VSoeEgiZFE5AW9YFuFkUbw&s=10', 'Madera MDF', 'Blanco', 160, 75, 90, 35, true, 18],
            ['Silla Escandinava', 'Sillas', 'Sala', 320000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSgDzDQ2nnpe9ws6s8uwmp049DomKoeebElGYXKIPS0Sg&s=10', 'Madera y tela', 'Beige', 55, 80, 55, 6, true, 12],
            ['Silla de Oficina Ergonómica', 'Sillas', 'Oficina', 590000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTMaUIwNt3OP8IFij6Oorny8A6lEYEMer9c9kJysDxQNA&s=10', 'Malla y metal', 'Negro', 65, 115, 65, 14, true, 24],
            ['Lámpara de Piso Trípode', 'Lámparas', 'Sala', 240000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8W6hsYPQGpAhjvZwY2x5rcJUmsZ6BrNONqGbuQztzbQ&s', 'Madera y tela', 'Natural', 40, 150, 40, 4, true, 12],
            ['Lámpara de Mesa Cerámica', 'Lámparas', 'Dormitorio', 130000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8XMUN9glMSZy3FOaNGk07IHaB1FrafP-7KxQvGONonQ&s=10', 'Cerámica', 'Blanco', 20, 45, 20, 2, false, 12],
            ['Cama Doble Tapizada', 'Sofás', 'Dormitorio', 2100000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR87ovbCGeU1Ot7leoDnqpOaoqUGir5w7PR8LMiSy7ggQ&s=10', 'Tela', 'Azul petróleo', 150, 110, 200, 55, true, 24],
            ['Set Silla y Mesa Exterior', 'Sillas', 'Exterior', 990000, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJo0kayh_vsAWDK4JzBk7aVKStKnjzZZvobG1nNoT8xw&s', 'Aluminio y ratán', 'Gris oscuro', 60, 90, 60, 8, true, 12],
        ];

        $productos = [];
        foreach ($datos as [$nombre, $cat, $amb, $precio, $imagen, $material, $color, $ancho, $alto, $prof, $peso, $ensamblaje, $garantia]) {
            $producto = Producto::create([
                'nombre' => $nombre,
                'descripcion' => "Mueble de la categoría {$cat}, ideal para {$amb}.",
                'imagen' => $imagen,
                'precio' => $precio,
                'stock' => rand(3, 20),
                'categoria_id' => $categorias[$cat]->id,
                'ambiente_id' => $ambientes[$amb]->id,
            ]);

            EspecificacionMueble::create([
                'producto_id' => $producto->id,
                'material' => $material,
                'color' => $color,
                'ancho_cm' => $ancho,
                'alto_cm' => $alto,
                'profundidad_cm' => $prof,
                'peso_kg' => $peso,
                'requiere_ensamblaje' => $ensamblaje,
                'garantia_meses' => $garantia,
            ]);

            $productos[] = $producto;
        }

        $pedido = Pedido::create([
            'user_id' => $cliente->id,
            'fecha' => now()->subDays(5),
            'estado' => 'entregado',
            'total' => 0,
        ]);

        foreach (array_slice($productos, 0, 4) as $i => $producto) {
            DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $producto->id,
                'cantidad' => 4 - $i,
                'precio_unitario' => $producto->precio,
            ]);
        }
        $pedido->update(['total' => $pedido->calcularTotal()]);

        foreach (array_slice($productos, 0, 6) as $i => $producto) {
            Resena::create([
                'user_id' => $cliente->id,
                'producto_id' => $producto->id,
                'calificacion' => rand(3, 5),
                'comentario' => 'Buen producto, cumple lo esperado.',
            ]);
        }
    }
}