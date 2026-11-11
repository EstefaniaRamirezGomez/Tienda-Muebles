{{-- Autor: Estefanía Ramírez Gómez --}}

@extends('layouts.admin')

@section('titulo', __('tienda.admin_inicio.dashboard'))

@section('contenido')


    {{-- ENCABEZADO DEL DASHBOARD --}}

    <h2>
        {{ __('tienda.admin_inicio.administration_dashboard') }}
    </h2>

    <p>
        {{ __('tienda.admin_inicio.manage_the_main_information_of_your') }}
    </p>

    {{-- MÉTRICAS GENERALES --}}

    <div class="metricas">

        <div class="metrica">
            <span>
                {{ __('tienda.admin_inicio.products') }}
            </span>

            <strong>
                {{ $resumen['productos'] }}
            </strong>
        </div>

        <div class="metrica">
            <span>
                {{ __('tienda.admin_inicio.categories') }}
            </span>

            <strong>
                {{ $resumen['categorias'] }}
            </strong>
        </div>

        <div class="metrica">
            <span>
                {{ __('tienda.admin_inicio.orders') }}
            </span>

            <strong>
                {{ $resumen['pedidos'] }}
            </strong>
        </div>

        <div class="metrica">
            <span>
                {{ __('tienda.admin_inicio.customers') }}
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
            {{ __('tienda.admin_inicio.inventory_reports') }}
        </h3>

        <p>
            {{ __('tienda.admin_inicio.download_a_report_containing_the_current') }}
        </p>

        <p
            style="
                font-size: 0.9rem;
                margin-bottom: 1.5rem;
            "
        >
            {{ __('tienda.admin_inicio.the_report_includes_product_id_name') }}
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
                {{ __('tienda.admin_inicio.download_csv') }}
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
                {{ __('tienda.admin_inicio.download_json') }}
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
                    {{ __('tienda.admin_inicio.csv_format') }}
                </strong>

                {{ __('tienda.admin_inicio.allows_you_to_view_and_analyze') }}

            </p>

            <p style="margin: 0;">

                <strong>
                    {{ __('tienda.admin_inicio.json_format') }}
                </strong>

                {{ __('tienda.admin_inicio.allows_you_to_use_the_data') }}

            </p>

        </div>

    </section>

    {{-- AVISO FINAL --}}

    <p
        class="aviso"
        style="margin-top: 1.5rem;"
    >
        {{ __('tienda.admin_inicio.use_the_sidebar_menu_to_manage') }}
    </p>

@endsection
