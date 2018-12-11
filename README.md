Amplifica Lumen Api Core

Pasos para instalar
 * Ejecutar el comando php artisan migrate, de manera que se creen las nuevas tablas de OAUTH2 y usuario en la base de datos.
 * Ejecutar el comando php artisan passport:install, para crear las claves de encriptación en la tabla "oauth_clients".
 
 NOTA: Si quisiéramos crear más claves, podríamos lanzar los siguientes comandos:

php artisan passport:client –password (para crear una clave de password)
php artisan passport:client –personal (para crear una clave personal)
