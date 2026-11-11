<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @yield('titulo', __('messages.products'))
        · {{ __('tienda.layouts_app.furniture_decoration') }}
    </title>

    <style>
        :root {
            --terracota: #B85042;
            --terracota-oscuro: #8C3A2F;
            --arena: #E7E8D1;
            --salvia: #A7BEAE;
            --carbon: #3A2E2A;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            background: #FAF8F5;
            color: var(--carbon);
            margin: 0;
        }

        header {
            background: var(--terracota-oscuro);
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        header h1 {
            margin: 0;
            font-size: 1.2rem;
        }

        nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        nav a {
            color: var(--arena);
            text-decoration: none;
            margin-left: 1.2rem;
            font-size: 0.9rem;
        }

        nav a:hover {
            color: #fff;
            text-decoration: underline;
        }

        main {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .tarjeta {
            background: #fff;
            border: 1px solid #E3DAD1;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .boton {
            display: inline-block;
            background: var(--terracota);
            color: #fff;
            padding: 0.55rem 1.1rem;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .boton:hover {
            background: var(--terracota-oscuro);
        }

        .boton-secundario {
            background: var(--salvia);
            color: var(--carbon);
        }

        .boton-eliminar {
            background: #C62828;
        }

        .boton-eliminar:hover {
            background: #A61B1B;
        }

        input[type="text"],
        input[type="search"],
        input[type="number"] {
            padding: 0.55rem;
            border: 1px solid #CFC5BB;
            border-radius: 6px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(
                auto-fill,
                minmax(220px, 1fr)
            );
            gap: 1rem;
            margin-top: 1rem;
        }

        .producto-card {
            background: #fff;
            border: 1px solid #E3DAD1;
            border-radius: 8px;
            padding: 1rem;
        }

        .producto-card h3 {
            margin: 0 0 0.3rem;
            font-size: 1rem;
        }

        .producto-card .precio {
            color: var(--terracota-oscuro);
            font-weight: 700;
        }

        .precio {
            color: var(--terracota-oscuro);
            font-weight: 700;
        }

        .etiqueta {
            display: inline-block;
            background: var(--arena);
            color: var(--terracota-oscuro);
            font-size: 0.75rem;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            margin-right: 0.3rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            text-align: left;
            padding: 0.6rem 0.5rem;
            border-bottom: 1px solid #ECE4DB;
            vertical-align: top;
        }

        th {
            color: var(--terracota-oscuro);
            background: #FBF6F1;
        }

        .estrella {
            color: var(--terracota);
        }

        .vacio {
            color: #8A7B70;
            font-style: italic;
        }

        .logo-inicio {
            color: #fff;
            text-decoration: none;
        }

        .logo-inicio:hover {
            color: var(--arena);
        }

        /* MENSAJES */

        .mensaje-exito {
            background: #E8F5E9;
            border: 1px solid #A5D6A7;
            padding: 0.8rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .mensaje-error {
            background: #FDECEC;
            border: 1px solid #EF9A9A;
            color: #B3261E;
            padding: 0.8rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        /* SESIÓN EN EL MENÚ */

        .form-salir {
            display: inline;
            margin-left: 1.2rem;
        }

        .form-salir button {
            background: none;
            border: 1px solid var(--arena);
            color: var(--arena);
            border-radius: 6px;
            padding: 0.25rem 0.7rem;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .form-salir button:hover {
            background: var(--arena);
            color: var(--terracota-oscuro);
        }

        /* CARRITO */

        .carrito-link {
            position: relative;
        }

        .carrito-contador {
            display: inline-block;
            min-width: 20px;
            padding: 0.1rem 0.4rem;
            margin-left: 0.2rem;
            background: var(--arena);
            color: var(--terracota-oscuro);
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 700;
            text-align: center;
        }

        /* FORMULARIOS LOGIN / REGISTRO */

        .formulario-auth {
            max-width: 420px;
            margin: 1rem auto;
        }

        .formulario-auth h2 {
            margin-top: 0;
        }

        .formulario-auth label {
            display: block;
            margin: 0.9rem 0 0.3rem;
            font-size: 0.9rem;
        }

        .formulario-auth input[type="text"],
        .formulario-auth input[type="email"],
        .formulario-auth input[type="password"] {
            width: 100%;
            padding: 0.55rem;
            border: 1px solid #CFC5BB;
            border-radius: 6px;
        }

        .formulario-auth .check {
            display: flex;
            gap: 0.4rem;
            align-items: center;
        }

        .formulario-auth .boton {
            margin-top: 1.2rem;
            width: 100%;
        }

        .error {
            color: #B3261E;
            font-size: 0.85rem;
            margin: 0.3rem 0 0;
        }

        .pie-auth {
            text-align: center;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        /* SELECTOR DE IDIOMAS */

        .selector-idioma {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            margin-left: 1.2rem;
            padding: 0.25rem;
            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 8px;
        }

        .selector-idioma a {
            margin: 0;
            padding: 0.3rem 0.55rem;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--arena);
            text-decoration: none;
        }

        .selector-idioma a:hover {
            color: #fff;
            background: rgba(255,255,255,0.15);
            text-decoration: none;
        }

        .selector-idioma a.activo {
            background: var(--arena);
            color: var(--terracota-oscuro);
        }

        /* DISEÑO RESPONSIVE */

        @media (max-width: 768px) {
            header {
                padding: 1rem;
            }

            nav {
                gap: 0.5rem;
            }

            nav a {
                margin-left: 0.5rem;
            }

            .selector-idioma {
                margin-left: 0.5rem;
            }

            .form-salir {
                margin-left: 0.5rem;
            }

            main {
                margin: 1.5rem auto;
                padding: 0 1rem;
            }
        }
    </style>
</head>

<body>

    <header>

        <h1>
            <a
                href="{{ route('inicio') }}"
                class="logo-inicio"
            >
                🪑 {{ __('tienda.layouts_app.furniture_decoration') }}
            </a>
        </h1>

        <nav>

            {{-- CATÁLOGO --}}
            <a href="{{ route('productos.index') }}">
                {{ __('tienda.layouts_app.catalog') }}
            </a>

            {{-- AMBIENTES --}}
            <a href="{{ route('ambientes.index') }}">
                {{ __('tienda.layouts_app.rooms') }}
            </a>

            {{-- DESTACADOS --}}
            <a href="{{ route('productos.destacados') }}">
                {{ __('messages.featured') }}
            </a>

            {{-- PRODUCTOS ALIADOS --}}
            <a href="{{ route('aliados.index') }}">
                {{ __('tienda.aliados.menu') }}
            </a>

            @auth

                @php
                    $cantidadCarrito = collect(
                        session('carrito', [])
                    )->sum('cantidad');
                @endphp

                {{-- CARRITO --}}
                <a
                    href="{{ route('carrito.index') }}"
                    class="carrito-link"
                >
                    🛒 {{ __('messages.cart') }}

                    <span class="carrito-contador">
                        {{ $cantidadCarrito }}
                    </span>
                </a>

                {{-- MIS PEDIDOS --}}
                <a href="{{ route('pedidos.index') }}">
                    {{ __('tienda.layouts_app.my_orders') }}
                </a>

                {{-- PANEL ADMINISTRADOR --}}
                @if (auth()->user()->esAdmin())

                    <a href="{{ route('admin.inicio') }}">
                        {{ __('tienda.layouts_app.admin_panel') }}
                    </a>

                @endif

                {{-- CERRAR SESIÓN --}}
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="form-salir"
                >
                    @csrf

                    <button type="submit">
                        {{ __('messages.logout') }}
                    </button>
                </form>

            @else

                {{-- INICIAR SESIÓN --}}
                <a href="{{ route('login') }}">
                    {{ __('messages.login') }}
                </a>

                {{-- REGISTRO --}}
                <a href="{{ route('register') }}">
                    {{ __('tienda.layouts_app.register') }}
                </a>

            @endauth

            {{-- SELECTOR DE IDIOMA --}}
            <div
                class="selector-idioma"
                aria-label="{{ __('tienda.layouts_app.select_language') }}"
            >

                <a
                    href="{{ route('idioma.cambiar', 'es') }}"
                    class="{{ app()->getLocale() === 'es' ? 'activo' : '' }}"
                    lang="es"
                    aria-label="Español"
                    aria-current="{{ app()->getLocale() === 'es' ? 'true' : 'false' }}"
                >
                    ES
                </a>

                <a
                    href="{{ route('idioma.cambiar', 'en') }}"
                    class="{{ __('tienda.layouts_app.activo') }}"
                    lang="en"
                    aria-label="English"
                    aria-current="{{ __('tienda.layouts_app.true') }}"
                >
                    EN
                </a>

            </div>

        </nav>

    </header>

    <main>
        @yield('contenido')
    </main>

</body>
</html>
