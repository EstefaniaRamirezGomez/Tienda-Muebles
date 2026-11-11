#!/bin/sh
# Autor: Equipo Muebles & Decoración
# Prepara Laravel cada vez que arranca el contenedor.
set -e

cd /var/www/html

# Esperar a que MySQL acepte conexiones.
echo "Esperando a MySQL en ${DB_HOST}:${DB_PORT:-3306}..."
until mysqladmin ping -h"${DB_HOST}" -P"${DB_PORT:-3306}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" --silent; do
    sleep 2
done

php artisan migrate --force

# Cargar los datos de prueba solo la primera vez (base de datos vacía).
if [ "$(php artisan tinker --execute='echo \App\Models\User::count();')" = "0" ]; then
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
