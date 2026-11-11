<?php

// Autor: Equipo Muebles & Decoración

namespace App\Services;

use App\Exceptions\CertificadoException;
use App\Models\Pedido;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Consume el servicio de blockchain del equipo anterior para emitir
 * y verificar certificados de autenticidad de los pedidos.
 *
 * Flujo: POST /transactions/new -> POST /mine -> GET /verify/{hash}
 */
class CertificadoBlockchainService
{
    /**
     * Registra el pedido en la blockchain, mina el bloque y guarda
     * el certificado en el pedido.
     *
     * @throws CertificadoException
     */
    public function certificar(Pedido $pedido): Pedido
    {
        $pedido->loadMissing('detalles.producto', 'usuario');

        $datos = $this->datosCertificado($pedido);
        $hash = $this->calcularHash($datos);

        $registro = $this->cliente()->post('/transactions/new', [
            'serial_number' => $datos['serial_number'],
            'certificate_hash' => $hash,
            'item_name' => 'Pedido #'.$pedido->id.' - Muebles & Decoración',
            'buyer' => $datos['comprador'],
            'price' => $datos['total'],
            'order_id' => $pedido->id,
            'items' => count($datos['productos']),
            'issued_at' => now()->toIso8601ZuluString(),
        ]);

        $this->exigirExito($registro, 'transactions/new');

        $minado = $this->cliente()->post('/mine');

        $this->exigirExito($minado, 'mine');

        $pedido->update([
            'certificado_serial' => $datos['serial_number'],
            'certificado_hash' => $hash,
            'certificado_bloque' => $minado->json('block.index'),
            'certificado_emitido_en' => now(),
        ]);

        return $pedido;
    }

    /**
     * Consulta al servicio si el certificado existe y la cadena es válida.
     *
     * @return array<string, mixed>|null null si el servicio no responde.
     */
    public function verificar(string $hash): ?array
    {
        try {
            $respuesta = $this->cliente()->get('/verify/'.$hash);
        } catch (\Throwable $e) {
            Log::warning('Blockchain: no se pudo verificar', ['error' => $e->getMessage()]);

            return null;
        }

        // Un 404 significa que el certificado no existe en la cadena.
        if ($respuesta->status() === 404) {
            return ['valid' => false] + ($respuesta->json() ?? []);
        }

        if (! $respuesta->successful() || ! is_array($respuesta->json())) {
            Log::warning('Blockchain: respuesta inválida en verify', ['status' => $respuesta->status()]);

            return null;
        }

        return $respuesta->json();
    }

    /**
     * Estado general de la red (GET /status).
     *
     * @return array<string, mixed>|null
     */
    public function estadoRed(): ?array
    {
        try {
            $respuesta = $this->cliente()->timeout(8)->get('/status');
        } catch (\Throwable) {
            return null;
        }

        return $respuesta->successful() && is_array($respuesta->json())
            ? $respuesta->json()
            : null;
    }

    /**
     * Comprueba si los datos actuales del pedido siguen produciendo
     * el mismo hash que se certificó (es decir, no fueron alterados).
     */
    public function datosIntactos(Pedido $pedido): bool
    {
        if (! $pedido->estaCertificado()) {
            return false;
        }

        $pedido->loadMissing('detalles.producto', 'usuario');

        return hash_equals(
            $pedido->certificado_hash,
            $this->calcularHash($this->datosCertificado($pedido))
        );
    }

    /**
     * Datos del pedido que forman el certificado.
     * Solo se usan datos que no cambian después de la compra.
     */
    public function datosCertificado(Pedido $pedido): array
    {
        return [
            'serial_number' => $pedido->certificado_serial ?? 'MYD-PED-'.str_pad((string) $pedido->id, 6, '0', STR_PAD_LEFT),
            'pedido_id' => $pedido->id,
            'fecha' => $pedido->created_at?->toIso8601ZuluString(),
            'comprador' => $pedido->nombre_receptor ?: $pedido->usuario?->name,
            'total' => (float) $pedido->total,
            'productos' => $pedido->detalles
                ->sortBy('id')
                ->map(fn ($detalle) => [
                    'producto_id' => $detalle->producto_id,
                    'cantidad' => (int) $detalle->cantidad,
                    'precio_unitario' => (float) $detalle->precio_unitario,
                ])
                ->values()
                ->all(),
        ];
    }

    /**
     * SHA-256 canónico: JSON con las claves ordenadas.
     */
    public function calcularHash(array $datos): string
    {
        return hash('sha256', json_encode(
            $this->ordenarClaves($datos),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        ));
    }

    private function ordenarClaves(array $datos): array
    {
        if (! array_is_list($datos)) {
            ksort($datos);
        }

        return array_map(
            fn ($valor) => is_array($valor) ? $this->ordenarClaves($valor) : $valor,
            $datos
        );
    }

    private function cliente(): PendingRequest
    {
        $url = config('services.blockchain.url');

        if (empty($url)) {
            throw new CertificadoException('BLOCKCHAIN_URL no está configurada.');
        }

        return Http::baseUrl(rtrim($url, '/'))
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('services.blockchain.timeout', 30));
    }

    /**
     * @throws CertificadoException
     */
    private function exigirExito($respuesta, string $endpoint): void
    {
        if (! $respuesta->successful()) {
            Log::warning('Blockchain: error en '.$endpoint, [
                'status' => $respuesta->status(),
                'body' => mb_substr($respuesta->body(), 0, 300),
            ]);

            throw new CertificadoException('Error en '.$endpoint.' ('.$respuesta->status().')');
        }
    }
}
