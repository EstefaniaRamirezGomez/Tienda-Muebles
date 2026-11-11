# Muebles & Decoración

Aplicación web desarrollada en Laravel 12 para una tienda virtual especializada en muebles y artículos de decoración para el hogar.

El proyecto utiliza la arquitectura MVC (Modelo - Vista - Controlador) con servicios e inversión de dependencias. Permite consultar productos, explorarlos por ambientes, buscarlos, compararlos, comprarlos (compra simulada) y gestionarlos desde un panel de administración. Está disponible en español e inglés.

## Integrantes

- Valentina Aguilar Correa
- Estefanía Ramírez Gómez

## Tecnologías utilizadas

- PHP 8.3 / Laravel 12
- Blade
- MySQL 8
- Docker y Docker Compose
- Git y GitHub

## Ejecutar con Docker (recomendado)

Requisitos: Docker y Docker Compose.

1. Clonar el repositorio:

   ```bash
   git clone https://github.com/EstefaniaRamirezGomez/Tienda-Muebles.git
   cd Tienda-Muebles
   ```

2. Crear el archivo de configuración y ajustar las claves de la base de datos:

   ```bash
   cp .env.example .env
   ```

   En `.env` cambie `DB_PASSWORD` y `DB_ROOT_PASSWORD`. Para producción use `APP_ENV=production` y `APP_DEBUG=false`.

3. Generar la clave de Laravel (sin tener PHP instalado):

   ```bash
   docker run --rm -v "$PWD":/app -w /app php:8.3-cli php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
   ```

   Copie el resultado en `APP_KEY=` dentro de `.env`.

4. Levantar los contenedores:

   ```bash
   docker compose up -d --build
   ```

   Al arrancar, el contenedor espera a MySQL, ejecuta las migraciones y, si la base está vacía, carga los datos de prueba.

5. Abrir `http://localhost/` (o la IP del servidor).

Comandos útiles:

```bash
docker compose logs -f app          # ver logs
docker compose exec app php artisan test   # pruebas unitarias
docker compose down                 # detener (los datos de MySQL se conservan)
```

## Ejecutar sin Docker

Requisitos: PHP 8.2+, Composer y MySQL.

```bash
composer install
cp .env.example .env      # configure DB_HOST, DB_DATABASE, DB_USERNAME y DB_PASSWORD
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

La aplicación queda en `http://127.0.0.1:8000/`.

## Usuarios de prueba

| Rol | Correo | Contraseña |
|-----|--------|------------|
| Administrador | admin@ejemplo.com | password |
| Cliente | cliente@ejemplo.com | password |

## Rutas principales

Usuario final:

- `/` - Página de inicio
- `/productos` - Catálogo de productos
- `/productos/buscar` - Búsqueda de productos
- `/productos/destacados` - Más vendidos y mejor calificados
- `/productos/comparar` - Comparación de productos
- `/ambientes` - Productos organizados por ambientes
- `/certificados/{hash}` - Verificación pública de un certificado blockchain (servicio consumido)
- `/carrito` y `/pedidos` - Compra simulada e historial (requiere sesión)
- `POST /pedidos/{id}/certificar` - Certifica un pedido en la blockchain del equipo aliado

Administrador (`/admin`, requiere rol admin):

- `/admin/productos` - CRUD de productos
- `/admin/pedidos` - Gestión de pedidos y estados de envío
- `/admin/reportes/productos?formato=csv|json` - Reporte de inventario

## Servicio web que proveemos (JSON)

- `GET /api/productos` - Productos en stock, con el enlace directo a cada producto.
- `GET /api/productos/{id}` - Detalle de un producto.

Ejemplo de respuesta:

```json
{
  "total": 10,
  "productos": [
    {
      "id": 1,
      "nombre": "Sofá Nórdico 3 puestos",
      "descripcion": "Mueble de la categoría Sofás, ideal para Sala.",
      "precio": "1890000.00",
      "stock": 17,
      "categoria": "Sofás",
      "ambiente": "Sala",
      "url": "http://IP-DEL-SERVIDOR/productos/1"
    }
  ]
}
```

## Servicios que consumimos

- **Equipo aliado (blockchain):** consumimos su servicio de certificados de autenticidad. En el detalle de cada pedido hay un botón "Certificar en blockchain": la app calcula el hash SHA-256 canónico del pedido, lo registra con `POST /transactions/new` y sella el bloque con `POST /mine`. La página `/certificados/{hash}` consulta `GET /verify/{hash}` y `GET /status`, muestra el resultado y además compara el hash con los datos actuales del pedido. La URL se configura en `.env` con `BLOCKCHAIN_URL`. Implementado en `app/Services/CertificadoBlockchainService.php` y `app/Http/Controllers/CertificadoController.php`. Si el servicio no responde, la tienda muestra un aviso y sigue funcionando.
- **MyMemory (servicio de un tercero):** API de traducción usada para traducir automáticamente al inglés el nombre y la descripción de los productos al guardarlos (`app/Services/TraduccionService.php`).

## Inversión de dependencias

`App\Contracts\ReporteProductoInterface` tiene dos implementaciones: `ReporteProductosCsv` y `ReporteProductosJson`. El binding está en `AppServiceProvider` y elige la clase según el parámetro `formato`; `ReporteProductoController` solo depende de la interfaz.

## Idiomas

Todos los textos están en `resources/lang/es` y `resources/lang/en`. El idioma se cambia desde el menú (`/idioma/es` o `/idioma/en`).

## Pruebas

```bash
php artisan test
```

Las pruebas unitarias de `tests/Unit/ReporteProductosTest.php` verifican los dos generadores de reportes.

## Datos de prueba (SQL)

`database/datos.sql` contiene las filas de prueba. Se genera desde el servidor (con los contenedores arriba) con:

```bash
sh docker/exportar-datos.sh
```

## Arquitectura

- **Modelos:** información y relaciones del dominio (`app/Models`).
- **Vistas:** Blade (`resources/views`), con layouts separados para tienda y administración.
- **Controladores:** reciben las solicitudes y coordinan la lógica (`app/Http/Controllers`).
- **Servicios:** lógica reutilizable y comunicación con APIs externas (`app/Services`).
- **Rutas:** `routes/web.php` (aplicación) y `routes/api.php` (servicio JSON).
