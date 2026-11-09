<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TraduccionService
{
    public function traducir(
        ?string $texto,
        string $origen = 'es',
        string $destino = 'en'
    ): ?string {
        if ($texto === null || trim($texto) === '') {
            return null;
        }

        $texto = trim($texto);

        if ($origen === $destino) {
            return $texto;
        }

        // MyMemory acepta hasta 500 bytes por solicitud.
        if (strlen($texto) > 500) {
            Log::warning(
                'Texto demasiado largo para MyMemory',
                ['bytes' => strlen($texto)]
            );

            return null;
        }

        try {
            $respuesta = Http::timeout(8)
                ->retry(2, 300, throw: false)
                ->acceptJson()
                ->get(
                    'https://api.mymemory.translated.net/get',
                    [
                        'q' => $texto,
                        'langpair' => $origen . '|' . $destino,
                    ]
                );

            if (!$respuesta->successful()) {
                Log::warning(
                    'Error HTTP al traducir',
                    ['status' => $respuesta->status()]
                );

                return null;
            }

            $datos = $respuesta->json();

            if (($datos['responseStatus'] ?? 0) != 200) {
                Log::warning(
                    'MyMemory rechazó la traducción',
                    [
                        'mensaje' =>
                            $datos['responseDetails'] ?? null,
                    ]
                );

                return null;
            }

            $traduccion = $datos[
                'responseData'
            ]['translatedText'] ?? null;

            if (!is_string($traduccion)) {
                return null;
            }

            $traduccion = trim(
                html_entity_decode(
                    $traduccion,
                    ENT_QUOTES | ENT_HTML5,
                    'UTF-8'
                )
            );

            return $traduccion !== ''
                ? $traduccion
                : null;

        } catch (\Throwable $e) {
            Log::warning(
                'No se pudo conectar con MyMemory',
                ['error' => $e->getMessage()]
            );

            return null;
        }
    }
}
