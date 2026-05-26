Guía de Instalación y Configuración - Sfouter
Este documento detalla los pasos necesarios para desplegar y ejecutar el proyecto Sfouter en un entorno de desarrollo local.

1. Requisitos Previos
Para ejecutar este proyecto, asegúrate de tener instalado:

PHP 8.x o superior.

Composer (Gestor de dependencias de PHP).

MySQL o MariaDB.

Un servidor web (se recomienda el servidor embebido de PHP para desarrollo).

2. Pasos para la Puesta en Marcha
A. Clonar el Repositorio

git clone [https://github.com/MarcoIsmaelGermanNavarro/Sfouter.git]
cd Sfouter

B. Instalar Dependencias
Instala todas las librerías necesarias definidas en composer.json:

composer install

C. Configurar Base de Datos
Crea una base de datos nueva en tu servidor MySQL local.

Importa el archivo sfouter_db.sql incluido en la raíz del proyecto para generar las tablas y datos.

Configura los parámetros de conexión en el archivo .env (o crea uno nuevo basado en la estructura necesaria):

DB_HOST=localhost
DB_NAME=nombre_de_tu_base_de_datos
DB_USER=tu_usuario_de_mysql
DB_PASS=tu_contraseña_de_mysql

D. Ejecución Local
Inicia el servidor web integrado de PHP apuntando a la carpeta public:

php -S localhost:8000 -t public/