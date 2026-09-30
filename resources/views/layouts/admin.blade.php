{{-- Autor: Estefanía Ramírez Gómez --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titulo', 'Panel de administración') · Muebles & Decoración</title>

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
            font-size: 0.9rem;
        }

        .barra form {
            margin: 0;
        }

        .barra button {
            background: none;
            border: 1px solid var(--borde);
            border-radius: 6px;
            padding: 0.35rem 0.8rem;
            cursor: pointer;
        }

        .barra button:hover {
            background: #F4F5F7;
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

    <aside>

        <h1>
            🪑 Panel de administración
        </h1>

        <a
            href="{{ route('admin.inicio') }}"
            class="{{ request()->routeIs('admin.inicio') ? 'activo' : '' }}"
        >
            Inicio
        </a>

        <a
            href="{{ route('admin.productos.index') }}"
            class="{{ request()->routeIs('admin.productos.*') ? 'activo' : '' }}"
        >
            Productos
        </a>

        <a href="{{ route('inicio') }}">
            Ver tienda
        </a>

    </aside>

    <div class="contenido">

        <div class="barra">

            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button type="submit">
                    Cerrar sesión
                </button>

            </form>

        </div>

        <main>
            @yield('contenido')
        </main>

    </div>

</body>
</html>