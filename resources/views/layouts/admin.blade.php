{{-- Autor: Estefanía Ramírez Gómez --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', __('app.admin_titulo')) · Admin</title>
    <style>
        :root {
            --fondo: #F4F5F7;
            --lateral: #1F2733;
            --lateral-texto: #C9D1DC;
            --acento: #3B6FD8;
            --texto: #1F2733;
            --borde: #E1E5EB;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            background: var(--fondo);
            color: var(--texto);
            display: flex;
            min-height: 100vh;
        }
        aside {
            width: 230px;
            background: var(--lateral);
            color: var(--lateral-texto);
            padding: 1.5rem 1rem;
            flex-shrink: 0;
        }
        aside h1 { color: #fff; font-size: 1.05rem; margin: 0 0 1.5rem; }
        aside a {
            display: block;
            color: var(--lateral-texto);
            text-decoration: none;
            padding: 0.55rem 0.75rem;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        aside a:hover, aside a.activo { background: rgba(255,255,255,0.08); color: #fff; }
        .contenido { flex: 1; display: flex; flex-direction: column; }
        .barra {
            background: #fff;
            border-bottom: 1px solid var(--borde);
            padding: 0.8rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
        }
        .barra form { margin: 0; }
        .barra button {
            background: none;
            border: 1px solid var(--borde);
            border-radius: 6px;
            padding: 0.35rem 0.8rem;
            cursor: pointer;
        }
        main { padding: 2rem; }
        .metricas {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }
        .metrica {
            background: #fff;
            border: 1px solid var(--borde);
            border-radius: 8px;
            padding: 1.2rem;
        }
        .metrica span { display: block; font-size: 0.8rem; color: #6B7686; }
        .metrica strong { font-size: 1.8rem; }
        .aviso {
            background: #fff;
            border-left: 4px solid var(--acento);
            padding: 1rem 1.2rem;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <aside>
        <h1>🪑 {{ __('app.admin_titulo') }}</h1>
        <a href="{{ route('admin.inicio') }}" class="activo">{{ __('app.admin_inicio') }}</a>
        <a href="{{ route('inicio') }}">{{ __('app.admin_ver_tienda') }}</a>
    </aside>

    <div class="contenido">
        <div class="barra">
            <span>{{ __('app.hola', ['nombre' => auth()->user()->name]) }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">{{ __('app.cerrar_sesion') }}</button>
            </form>
        </div>

        <main>
            @yield('contenido')
        </main>
    </div>
</body>
</html>
