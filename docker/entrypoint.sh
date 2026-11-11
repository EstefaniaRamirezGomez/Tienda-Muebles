#!/bin/sh
# Autor: Equipo Muebles & Decoración
# Prepara Laravel cada vez que arranca el contenedor.
set -e

cd /var/www/html

# Esperar a que MySQL acepte conexiones (se prueba con PDO, igual que Laravel).
echo "Esperando a MySQL en ${DB_HOST}:${DB_PORT:-3306}..."
until php -r '
    try {
        new PDO(
            "mysql:host=" . getenv("DB_HOST") . ";port=" . (getenv("DB_PORT") ?: 3306),
            getenv("DB_USERNAME"),
            getenv("DB_PASSWORD")
        );
    } catch (Throwable $e) {
        exit(1);
    }
' 2>/dev/null; do
    sleep 2
done

echo "MySQL listo."

php artisan migrate --force

# Cargar los datos de prueba solo la primera vez (base de datos vacía).
if [ "$(php artisan tinker --execute='echo \App\Models\User::count();')" = "0" ]; then
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
