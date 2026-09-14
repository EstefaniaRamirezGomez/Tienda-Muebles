<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('titulo', 'Catálogo') · Muebles &amp; Decoración</title>
    <style>
        :root {
            --terracota: #B85042;
            --terracota-oscuro: #8C3A2F;
            --arena: #E7E8D1;
            --salvia: #A7BEAE;
            --carbon: #3A2E2A;
        }
        * { box-sizing: border-box; }
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
            gap: 0.5rem;
        }
        header h1 { margin: 0; font-size: 1.2rem; }
        nav a {
            color: var(--arena);
            text-decoration: none;
            margin-left: 1.2rem;
            font-size: 0.9rem;
        }
        nav a:hover { color: #fff; text-decoration: underline; }
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
        .boton:hover { background: var(--terracota-oscuro); }
        .boton-secundario { background: var(--salvia); color: var(--carbon); }
        input[type="text"], input[type="search"] {
            padding: 0.55rem;
            border: 1px solid #CFC5BB;
            border-radius: 6px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .producto-card {
            background: #fff;
            border: 1px solid #E3DAD1;
            border-radius: 8px;
            padding: 1rem;
        }
        .producto-card h3 { margin: 0 0 0.3rem; font-size: 1rem; }
        .producto-card .precio { color: var(--terracota-oscuro); font-weight: 700; }
        .etiqueta {
            display: inline-block;
            background: var(--arena);
            color: var(--terracota-oscuro);
            font-size: 0.75rem;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            margin-right: 0.3rem;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { text-align: left; padding: 0.6rem 0.5rem; border-bottom: 1px solid #ECE4DB; vertical-align: top; }
        th { color: var(--terracota-oscuro); background: #FBF6F1; }
        .estrella { color: var(--terracota); }
        .vacio { color: #8A7B70; font-style: italic; }
    </style>
</head>
<body>
    <header>
        <h1>🪑 Muebles &amp; Decoración</h1>
        <nav>
            <a href="{{ route('productos.index') }}">Catálogo</a>
            <a href="{{ route('ambientes.index') }}">Ambientes</a>
            <a href="{{ route('productos.destacados') }}">Destacados</a>
        </nav>
    </header>
    <main>
        @yield('contenido')
    </main>
</body>
</html>