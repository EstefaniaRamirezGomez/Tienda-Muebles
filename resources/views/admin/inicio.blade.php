{{-- Autor: Estefanía Ramírez Gómez --}}

@extends('layouts.admin')

@section('titulo', app()->getLocale() === 'en' ? 'Dashboard' : 'Inicio')

@section('contenido')

    @php
        $ingles = app()->getLocale() === 'en';
    @endphp

    {{-- ENCABEZADO DEL DASHBOARD --}}

    <h2>
        {{ $ingles ? 'Administration Dashboard' : 'Panel de administración' }}
    </h2>

    <p>
        {{ $ingles
            ? 'Manage the main information of your store.'
            : 'Gestiona la información principal de la tienda.' }}
    </p>

    {{-- MÉTRICAS GENERALES --}}

    <div class="metricas">

        <div class="metrica">
            <span>
                {{ $ingles ? 'Products' : 'Productos' }}
            </span>

            <strong>
                {{ $resumen['productos'] }}
            </strong>
        </div>

        <div class="metrica">
            <span>
                {{ $ingles ? 'Categories' : 'Categorías' }}
            </span>

            <strong>
                {{ $resumen['categorias'] }}
            </strong>
        </div>

        <div class="metrica">
            <span>
                {{ $ingles ? 'Orders' : 'Pedidos' }}
            </span>

            <strong>
                {{ $resumen['pedidos'] }}
            </strong>
        </div>

        <div class="metrica">
            <span>
                {{ $ingles ? 'Customers' : 'Clientes' }}
            </span>

            <strong>
                {{ $resumen['clientes'] }}
            </strong>
        </div>

    </div>

    {{-- REPORTES DE INVENTARIO --}}

    <section
        class="tarjeta"
        style="
            margin-top: 2rem;
            padding: 1.5rem;
            background: #fff;
            border: 1px solid #E1E5EB;
            border-radius: 8px;
        "
    >

        <h3>
            {{ $ingles ? 'Inventory Reports' : 'Reportes de inventario' }}
        </h3>

        <p>
            {{ $ingles
                ? 'Download a report containing the current information of the products registered in the store.'
                : 'Descarga un reporte con la información actual de los productos registrados en la tienda.' }}
        </p>

        <p
            style="
                font-size: 0.9rem;
                margin-bottom: 1.5rem;
            "
        >
            {{ $ingles
                ? 'The report includes product ID, name, price, available stock, and category.'
                : 'El reporte incluye identificador, nombre, precio, stock disponible y categoría.' }}
        </p>

        {{-- OPCIONES DE DESCARGA --}}

        <div
            style="
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
                align-items: center;
            "
        >

            {{-- DESCARGAR CSV --}}

            <a
                href="{{ route('admin.reportes.productos', ['formato' => 'csv']) }}"
                class="boton"
                style="
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5rem;
                    padding: 0.75rem 1.25rem;
                    text-decoration: none;
                    border-radius: 8px;
                "
            >
                &#11015;
                {{ $ingles ? 'Download CSV' : 'Descargar CSV' }}
            </a>

            {{-- DESCARGAR JSON --}}

            <a
                href="{{ route('admin.reportes.productos', ['formato' => 'json']) }}"
                class="boton"
                style="
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5rem;
                    padding: 0.75rem 1.25rem;
                    text-decoration: none;
                    border-radius: 8px;
                "
            >
                &#11015;
                {{ $ingles ? 'Download JSON' : 'Descargar JSON' }}
            </a>

        </div>

        {{-- EXPLICACIÓN DE FORMATOS --}}

        <div
            style="
                margin-top: 1.5rem;
                padding: 1rem;
                background-color: #f8f5f2;
                border-radius: 8px;
                font-size: 0.9rem;
                color: #5d514b;
            "
        >

            <p style="margin-bottom: 0.5rem;">

                <strong>
                    {{ $ingles ? 'CSV Format:' : 'Formato CSV:' }}
                </strong>

                {{ $ingles
                    ? 'Allows you to view and analyze data in Excel and other spreadsheet applications.'
                    : 'Permite consultar los datos en Excel y otras herramientas de hojas de cálculo.' }}

            </p>

            <p style="margin: 0;">

                <strong>
                    {{ $ingles ? 'JSON Format:' : 'Formato JSON:' }}
                </strong>

                {{ $ingles
                    ? 'Allows you to use the data in applications, services, and external systems.'
                    : 'Permite utilizar los datos en aplicaciones, servicios y sistemas externos.' }}

            </p>

        </div>

    </section>

    {{-- AVISO FINAL --}}

    <p
        class="aviso"
        style="margin-top: 1.5rem;"
    >
        {{ $ingles
            ? 'Use the sidebar menu to manage the products in your store.'
            : 'Utiliza el menú lateral para administrar los productos de la tienda.' }}
    </p>

@endsection
