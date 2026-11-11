#!/bin/sh
# Autor: Equipo Muebles & Decoración
# Exporta las filas (datos ficticios) de MySQL a database/datos.sql.
# Ejecutar en el servidor, desde la carpeta del proyecto, con los contenedores arriba:
#   sh docker/exportar-datos.sh
set -e
. ./.env
docker compose exec -T db mysqldump \
    --no-create-info --skip-triggers --complete-insert \
    -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" \
    users categorias ambientes productos especificaciones_muebles pedidos detalle_pedidos resenas \
    > database/datos.sql
echo "Listo: database/datos.sql"
