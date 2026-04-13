# Pasos a seguir para ejecutar en entorno local (Docker).

1. Tener Docker instalado y en funcionamiento. 

2. Levantar el proyecto, ejecutando en el terminal desde la carpeta del proyecto el siguiente comando: 
docker compose up -d --build 


# Acceso en local. 

- Aplicación web: http://localhost:8080
- phpMyAdmin: http://localhost:8081


# Base de datos local.

Base de datos reparaya. 
- usuario y contraseña: root

La base de datos se encuentra en la carpeta /sql/reparaya.sql 


# Acceso servidor (AWS). 

- phpMyAdmin: http://fp064.techlab.uoc.edu/bbdd/
Usuario: wordpress5
Contraseña: 2ZNG53TdCaOoLpvp


# Configuración automática del entorno. 

El archivo config.php detecta automáticamente si la aplicación se ejecuta en local o en servidor. 
Esto permite ejecutar el proyecto en ambos entornos sin necesidad de modificar el código. 


# Estructura del proyecto. 

- app/ --> Controladores y modelo. 
- public/ --> index.php y vistas. 
- config/ --> Script de la base de datos. 
- docker-compose.yml --> Configuración de contenedores. 


# Funcionalidades del programa. 

1. Registro y login de usuarios. 
2. Creación de incidencias. 
3. Listado de incidencias del cliente. 
4. Cancelación de incidencias (regla de las 48 h).
5. Panel de administración. 
6. Asignación de técnicos. 
