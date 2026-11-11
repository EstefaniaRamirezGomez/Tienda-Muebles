{{-- Autor: Estefanía Ramírez Gómez --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>
        @yield('titulo', __('tienda.layouts_admin.administration_panel'))
        · Muebles & Decoración
    </title>

    <style>
        :root {
            --fondo: #F4F5F7;
            --lateral: #1F2733;
            --lateral-texto: #C9D1DC;
            --acento: #3B6FD8;
            --texto: #1F2733;
            --borde: #E1E5EB;
            --error: #B3261E;
            --exito-fondo: #E8F5E9;
            --exito-borde: #A5D6A7;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            background: var(--fondo);
            color: var(--texto);
            display: flex;
            min-height: 100vh;
        }

        /* =========================
           MENÚ LATERAL
        ========================= */

        aside {
            width: 230px;
            background: var(--lateral);
            color: var(--lateral-texto);
            padding: 1.5rem 1rem;
            flex-shrink: 0;
        }

        aside h1 {
            color: #fff;
            font-size: 1.05rem;
            margin: 0 0 1.5rem;
        }

        aside a {
            display: block;
            color: var(--lateral-texto);
            text-decoration: none;
            padding: 0.55rem 0.75rem;
            border-radius: 6px;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        aside a:hover,
        aside a.activo {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        /* =========================
           CONTENIDO PRINCIPAL
        ========================= */

        .contenido {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .barra {
            background: #fff;
            border-bottom: 1px solid var(--borde);
            padding: 0.8rem 2rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            font-size: 0.9rem;
        }

        .barra form {
            margin: 0;
        }

        .barra button {
            background: none;
            border: 1px solid var(--borde);
            border-radius: 6px;
            padding: 0.45rem 0.8rem;
            cursor: pointer;
            font: inherit;
        }

        .barra button:hover {
            background: #F4F5F7;
        }

        /* =========================
           SELECTOR DE IDIOMA
        ========================= */

        .selector-idioma {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .selector-idioma span {
            font-size: 0.85rem;
            color: #6B7686;
            margin-right: 0.3rem;
        }

        .selector-idioma a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.45rem 0.75rem;
            border: 1px solid var(--borde);
            border-radius: 6px;
            text-decoration: none;
            color: var(--texto);
            font-size: 0.85rem;
            transition: background 0.2s;
        }

        .selector-idioma a:hover {
            background: #F4F5F7;
        }

        .selector-idioma a.activo {
            background: var(--acento);
            border-color: var(--acento);
            color: #fff;
            font-weight: 600;
        }

        main {
            padding: 2rem;
        }

        /* =========================
           PANEL PRINCIPAL
        ========================= */

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

        .metrica span {
            display: block;
            font-size: 0.8rem;
            color: #6B7686;
        }

        .metrica strong {
            font-size: 1.8rem;
        }

        .aviso {
            background: #fff;
            border-left: 4px solid var(--acento);
            padding: 1rem 1.2rem;
            border-radius: 4px;
        }

        /* =========================
           BOTONES
        ========================= */

        .boton {
            display: inline-block;
            background: var(--acento);
            color: #fff;
            padding: 0.55rem 1rem;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .boton:hover {
            opacity: 0.9;
        }

        .boton-eliminar {
            background: #C62828;
            margin-left: 0.4rem;
        }

        .boton-eliminar:hover {
            background: #A61B1B;
        }

        /* =========================
           PRODUCTOS
        ========================= */

        .encabezado-admin {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .encabezado-admin h2 {
            margin-bottom: 0.3rem;
        }

        .encabezado-admin p {
            margin-top: 0;
            color: #6B7686;
        }

        /* =========================
           FORMULARIOS
        ========================= */

        .tarjeta-admin {
            background: #fff;
            border: 1px solid var(--borde);
            border-radius: 8px;
            padding: 1.5rem;
            max-width: 700px;
        }

        .campo {
            margin-bottom: 1rem;
        }

        .campo label {
            display: block;
            margin-bottom: 0.35rem;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .campo input,
        .campo textarea,
        .campo select {
            width: 100%;
            padding: 0.65rem;
            border: 1px solid var(--borde);
            border-radius: 6px;
            font: inherit;
            background: #fff;
        }

        .campo input:focus,
        .campo textarea:focus,
        .campo select:focus {
            outline: none;
            border-color: var(--acento);
        }

        .campo textarea {
            resize: vertical;
        }

        .error {
            color: var(--error);
            font-size: 0.85rem;
            margin-top: 0.3rem;
        }

        /* =========================
           MENSAJES
        ========================= */

        .mensaje-exito {
            background: var(--exito-fondo);
            border: 1px solid var(--exito-borde);
            padding: 0.8rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .mensaje-error {
            background: #FDECEA;
            border: 1px solid #F5C2C0;
            padding: 0.8rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        /* =========================
           TABLA
        ========================= */

        .tabla-contenedor {
            background: #fff;
            border: 1px solid var(--borde);
            border-radius: 8px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid var(--borde);
            vertical-align: middle;
        }

        th {
            background: #F8F9FA;
            font-size: 0.85rem;
        }

        td {
            font-size: 0.9rem;
        }

        tbody tr:hover {
            background: #FAFBFC;
        }

        td img {
            width: 80px;
            height: 55px;
            border-radius: 6px;
            object-fit: cover;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 768px) {

            body {
                display: block;
            }

            aside {
                width: 100%;
            }

            aside a {
                display: inline-block;
                margin-right: 0.3rem;
            }

            .barra {
                padding: 0.8rem 1rem;
                justify-content: flex-start;
            }

            main {
                padding: 1rem;
            }

            .encabezado-admin {
                flex-direction: column;
                align-items: flex-start;
            }
        }

    </style>
</head>

<body>

    {{-- MENÚ LATERAL --}}
    <aside>

        <h1>
            🪑 {{ __('tienda.layouts_admin.administration_panel') }}
        </h1>

        {{-- INICIO --}}
        <a
            href="{{ route('admin.inicio') }}"
            class="{{ request()->routeIs('admin.inicio') ? 'activo' : '' }}"
        >
            {{ __('tienda.layouts_admin.dashboard') }}
        </a>

        {{-- PRODUCTOS --}}
        <a
            href="{{ route('admin.productos.index') }}"
            class="{{ request()->routeIs('admin.productos.*') ? 'activo' : '' }}"
        >
            {{ __('tienda.layouts_admin.products') }}
        </a>

        {{-- PEDIDOS --}}
        <a
            href="{{ route('admin.pedidos.index') }}"
            class="{{ request()->routeIs('admin.pedidos.*') ? 'activo' : '' }}"
        >
            {{ __('tienda.layouts_admin.orders') }}
        </a>

        {{-- VER TIENDA --}}
        <a href="{{ route('inicio') }}">
            {{ __('tienda.layouts_admin.view_store') }}
        </a>

    </aside>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="contenido">

        {{-- BARRA SUPERIOR --}}
        <div class="barra">

            {{-- SELECTOR DE IDIOMA --}}
            <div
                class="selector-idioma"
                aria-label="{{ __('tienda.layouts_admin.language_selector') }}"
            >

                <span>
                    {{ __('tienda.layouts_admin.language') }}
                </span>

                <a
                    href="{{ route('idioma.cambiar', 'es') }}"
                    class="{{ !__('tienda.layouts_admin.activo') }}"
                    lang="es"
                    aria-current="{{ !__('tienda.layouts_admin.true') }}"
                >
                    🇨🇴 Español
                </a>

                <a
                    href="{{ route('idioma.cambiar', 'en') }}"
                    class="{{ __('tienda.layouts_admin.activo') }}"
                    lang="en"
                    aria-current="{{ __('tienda.layouts_admin.true') }}"
                >
                    🇺🇸 English
                </a>

            </div>

            {{-- CERRAR SESIÓN --}}
            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button type="submit">
                    {{ __('tienda.layouts_admin.log_out') }}
                </button>

            </form>

        </div>

        {{-- CONTENIDO DE CADA VISTA --}}
        <main>
            @yield('contenido')
        </main>

    </div>

</body>
</html>
