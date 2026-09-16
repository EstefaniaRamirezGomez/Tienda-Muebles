# Muebles & Decoración

Aplicación web desarrollada en Laravel 12 para una tienda virtual especializada en muebles y artículos de decoración para el hogar.

El proyecto utiliza la arquitectura MVC (Modelo - Vista - Controlador) y permite consultar productos, explorar muebles por ambientes, buscar productos y comparar diferentes opciones. También contará con una sección administrativa para gestionar la información de la tienda.

## Integrantes

- Valentina Aguilar Correa
- Estefanía Ramírez Gómez

## Tecnologías utilizadas

- PHP
- Laravel 12
- Blade
- HTML
- CSS
- MySQL
- Git y GitHub

## Requisitos

Para ejecutar el proyecto se necesita:

- PHP 8.2 o superior
- Composer
- MySQL
- Git

## Instalación y ejecución

1. Clonar el repositorio:

   git clone https://github.com/EstefaniaRamirezGomez/Tienda-Muebles.git

2. Entrar a la carpeta del proyecto:

   cd Tienda-Muebles

3. Instalar las dependencias:

   composer install

4. Crear el archivo de configuración:

   cp .env.example .env

5. Generar la clave de Laravel:

   php artisan key:generate

6. Configurar la conexión a MySQL en el archivo `.env`.

7. Ejecutar las migraciones:

   php artisan migrate

8. Iniciar el servidor:

   php artisan serve

## Ruta principal

Después de iniciar el servidor, la aplicación se puede abrir desde:

http://127.0.0.1:8000/

Esta es la ruta principal de la tienda.

## Rutas principales

- `/` - Página de inicio
- `/productos` - Catálogo de productos
- `/productos/buscar` - Búsqueda de productos
- `/productos/destacados` - Productos destacados
- `/productos/comparar` - Comparación de productos
- `/ambientes` - Productos organizados por ambientes

## Arquitectura

El proyecto utiliza el patrón MVC de Laravel:

- **Modelos:** representan la información y las relaciones del dominio.
- **Vistas:** desarrolladas con Blade para presentar la información.
- **Controladores:** reciben las solicitudes y coordinan la lógica de la aplicación.
- **Rutas:** definidas principalmente en `routes/web.php`.