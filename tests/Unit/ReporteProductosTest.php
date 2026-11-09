<?php

namespace Tests\Unit;

use App\Contracts\ReporteProductoInterface;
use App\Models\Producto;
use App\Services\ReporteProductosCsv;
use App\Services\ReporteProductosJson;
use Tests\TestCase;

class ReporteProductosTest extends TestCase
{
    /**
     * Verifica que el generador CSV cumple el contrato
     * y exporta correctamente los datos.
     */
    public function test_genera_reporte_csv_correctamente(): void
    {
        $producto = new Producto();

        $producto->id = 1;
        $producto->nombre = 'Mesa de madera';
        $producto->precio = 250000;
        $producto->stock = 10;

        $productos = collect([$producto]);

        $generador = new ReporteProductosCsv();

        $this->assertInstanceOf(
            ReporteProductoInterface::class,
            $generador
        );

        $respuesta = $generador->generar($productos);

        $this->assertEquals(
            200,
            $respuesta->getStatusCode()
        );

        $this->assertStringContainsString(
            'text/csv',
            $respuesta->headers->get('Content-Type')
        );

        $contenido = $respuesta->getContent();

        $this->assertStringContainsString(
            'Mesa de madera',
            $contenido
        );

        $this->assertStringContainsString(
            '250000',
            $contenido
        );

        $this->assertStringContainsString(
            '10',
            $contenido
        );
    }

    /**
     * Verifica que el generador JSON cumple el contrato
     * y produce la estructura esperada.
     */
    public function test_genera_reporte_json_correctamente(): void
    {
        $producto = new Producto();

        $producto->id = 2;
        $producto->nombre = 'Silla moderna';
        $producto->precio = 150000;
        $producto->stock = 5;

        $productos = collect([$producto]);

        $generador = new ReporteProductosJson();

        $this->assertInstanceOf(
            ReporteProductoInterface::class,
            $generador
        );

        $respuesta = $generador->generar($productos);

        $this->assertEquals(
            200,
            $respuesta->getStatusCode()
        );

        $this->assertStringContainsString(
            'application/json',
            $respuesta->headers->get('Content-Type')
        );

        $datos = json_decode(
            $respuesta->getContent(),
            true
        );

        $this->assertIsArray($datos);

        $this->assertEquals(
            1,
            $datos['total']
        );

        $this->assertCount(
            1,
            $datos['productos']
        );

        $this->assertEquals(
            2,
            $datos['productos'][0]['id']
        );

        $this->assertEquals(
            'Silla moderna',
            $datos['productos'][0]['nombre']
        );

        $this->assertEquals(
            150000,
            $datos['productos'][0]['precio']
        );

        $this->assertEquals(
            5,
            $datos['productos'][0]['stock']
        );
    }
}
