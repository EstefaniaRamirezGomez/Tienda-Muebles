<?php

// Autor: Equipo Muebles & Decoración

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Services\CertificadoBlockchainService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Consume el servicio de blockchain del equipo anterior:
 * certifica pedidos y verifica certificados de autenticidad.
 */
class CertificadoController extends Controller
{
    public function __construct(
        private readonly CertificadoBlockchainService $blockchain
    ) {}

    /**
     * Emite el certificado de un pedido (dueño del pedido o administrador).
     */
    public function certificar(Pedido $pedido): RedirectResponse
    {
        abort_unless(
            $pedido->user_id === auth()->id() || auth()->user()->esAdmin(),
            403
        );

        if ($pedido->estaCertificado()) {
            return back()->with('exito', __('tienda.certificados.ya_certificado'));
        }

        if ($pedido->estado === 'cancelado') {
            return back()->with('error', __('tienda.certificados.pedido_cancelado'));
        }

        try {
            $this->blockchain->certificar($pedido);
        } catch (\Throwable $e) {
            Log::warning('No se pudo certificar el pedido', [
                'pedido' => $pedido->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', __('tienda.certificados.error_certificar'));
        }

        return redirect()
            ->route('certificados.verificar', $pedido->certificado_hash)
            ->with('exito', __('tienda.certificados.certificado_emitido'));
    }

    /**
     * Página pública para verificar la autenticidad de un certificado.
     */
    public function verificar(string $hash): View
    {
        $pedido = Pedido::with('detalles.producto', 'usuario')
            ->where('certificado_hash', $hash)
            ->first();

        return view('certificados.verificar', [
            'hash' => $hash,
            'pedido' => $pedido,
            'verificacion' => $this->blockchain->verificar($hash),
            'datosIntactos' => $pedido ? $this->blockchain->datosIntactos($pedido) : null,
            'estadoRed' => $this->blockchain->estadoRed(),
        ]);
    }
}
