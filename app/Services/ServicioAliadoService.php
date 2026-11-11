<?php

// Autor: Equipo Muebles & Decoración

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Consume el servicio JSON del equipo aliado (equipo anterior)
 * y lo convierte a una estructura común para la vista.
 */
class ServicioAliadoService
{
    /**
     * @return array<int, array<string, mixed>>|null null si el servicio no responde.
     */
    public function obtenerProductos(): ?array
    {
        $url = config('services.aliado.url');

        if (empty($url)) {
            return null;
        }

        return Cache::remember('servicio_aliado_productos', now()->addMinutes(10), function () use ($url) {
            try {
                $respuesta = Http::timeout(8)->acceptJson()->get($url);
            } catch (\Throwable $e) {
                Log::warning('No se pudo conectar con el servicio aliado', ['error' => $e->getMessage()]);

                return null;
            }

            if (! $respuesta->successful()) {
                Log::warning('El servicio aliado respondió con error', ['status' => $respuesta->status()]);

                return null;
            }

            return $this->normalizar($respuesta->json());
        }) ?? $this->olvidarSiFalla();
    }

    /**
     * Acepta una lista directa o un objeto con la lista dentro
     * (data, productos, products, items, results).
     */
    private function normalizar(mixed $json): array
    {
        if (! is_array($json)) {
            return [];
        }

        if (! array_is_list($json)) {
            foreach (['data', 'productos', 'products', 'items', 'results'] as $clave) {
                if (isset($json[$clave]) && is_array($json[$clave])) {
                    $json = $json[$clave];
                    break;
                }
            }
        }

        if (! array_is_list($json)) {
            return [];
        }

        return collect($json)
            ->filter(fn ($item) => is_array($item))
            ->map(fn (array $item) => [
                'nombre' => $this->primero($item, ['nombre', 'name', 'title', 'titulo']),
                'descripcion' => $this->primero($item, ['descripcion', 'description', 'detalle']),
                'precio' => $this->primero($item, ['precio', 'price', 'valor']),
                'imagen' => $this->primero($item, ['imagen', 'image', 'img', 'image_url', 'foto']),
                'url' => $this->primero($item, ['url', 'link', 'enlace', 'href']),
            ])
            ->filter(fn (array $item) => $item['nombre'] !== null)
            ->values()
            ->all();
    }

    private function primero(array $item, array $claves): mixed
    {
        foreach ($claves as $clave) {
            $valor = Arr::get($item, $clave);

            if ($valor !== null && $valor !== '') {
                return $valor;
            }
        }

        return null;
    }

    /**
     * Si la consulta falló, no se guarda el null en caché
     * para volver a intentar en la siguiente visita.
     */
    private function olvidarSiFalla(): ?array
    {
        Cache::forget('servicio_aliado_productos');

        return null;
    }
}
