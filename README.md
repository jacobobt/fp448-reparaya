# ReparaYa - Producto 3 Laravel

Aplicacion de gestion de reparaciones domesticas migrada a Laravel para el Producto 3 de FP.448. El proyecto adapta la aplicacion del Producto 2, desarrollada en PHP sin framework, a una estructura Laravel con rutas, controladores, modelos Eloquent, vistas Blade, migraciones y un Web Service REST.

## Objetivo del producto

El Producto 3 consiste en migrar ReparaYa a Laravel y ampliar el modelo de negocio con administradores de fincas o gestoras.

Funcionalidades principales:

- Login, registro y logout de usuarios.
- Menus dinamicos segun rol: administrador, tecnico, particular y gestora.
- Gestion de incidencias y asignacion de tecnicos.
- Calendario de incidencias para administracion.
- Agenda de trabajo para tecnicos.
- Panel B2B para gestoras.
- Gestion de comunidades, zonas y liquidaciones.
- Calculo de comisiones para gestoras.
- API JSON `/api/servicios/zonas` con estadisticas por zona.

## Puesta en marcha local

Requisitos:

- PHP instalado en local.
- Composer instalado.
- Docker Desktop abierto.
- MySQL levantado mediante `docker compose`.

Pasos habituales:

```bash
composer install
```

Instala las dependencias PHP definidas en `composer.lock`.

```bash
cp .env.example .env
php artisan key:generate
```

Crea el archivo de entorno local y genera la clave de aplicacion Laravel.

```bash
docker compose up -d
```

Levanta los contenedores necesarios, principalmente MySQL y phpMyAdmin.

```bash
php artisan migrate:fresh --seed
```

Recrea las tablas desde las migraciones y carga datos de prueba con seeders.

```bash
php artisan serve
```

Arranca la aplicacion en:

```text
http://127.0.0.1:8000
```

phpMyAdmin queda disponible normalmente en:

```text
http://localhost:8081
```

## Usuarios de prueba

Todos los usuarios de prueba usan la contrasena `1234`.

| Rol | Email | Contrasena |
| --- | --- | --- |
| Administrador | `admin@reparaya.edu` | `1234` |
| Tecnico | `nfontanero@reparaya.edu` | `1234` |
| Tecnico | `mcerrajero@reparaya.edu` | `1234` |
| Tecnico | `pelectricista@reparaya.edu` | `1234` |
| Particular | `jperez@gmail.com` | `1234` |
| Particular | `antonia1965@gmail.com` | `1234` |
| Gestora | `gestora@reparaya.edu` | `1234` |

## Rutas importantes

Rutas web:

```text
/
/login
/registro
/mis-avisos
/avisos/nuevo
/admin
/admin/calendario
/admin/incidencias
/admin/tecnicos
/admin/gestoras
/admin/zonas
/admin/comunidades
/admin/liquidaciones
/tecnico/agenda
/gestora
/gestora/avisos/nuevo
/gestora/liquidaciones
```

Ruta API:

```text
/api/servicios/zonas
```

Ejemplo de respuesta:

```json
{
  "total_servicios_global": 0,
  "zonas": [
    {
      "nombre_zona": "Centro",
      "total_servicios": 0,
      "porcentaje": 0
    }
  ]
}
```

La API cuenta servicios en estado `Finalizada`. Si los datos de prueba estan en estado `Asignada`, la respuesta puede mostrar todos los totales a cero.

## Estructura Laravel del proyecto

La aplicacion sigue el patron MVC de Laravel:

```text
Ruta -> Controlador -> Modelo Eloquent -> Base de datos -> Vista Blade
```

Carpetas principales:

- `routes/web.php`: rutas de la aplicacion web.
- `routes/api.php`: rutas que devuelven JSON.
- `app/Http/Controllers`: logica de cada modulo.
- `app/Http/Middleware`: restricciones de acceso por rol.
- `app/Models`: modelos Eloquent que representan tablas.
- `resources/views`: plantillas Blade.
- `database/migrations`: definicion versionada de las tablas.
- `database/seeders`: datos iniciales de prueba.

## Controladores principales

- `AuthController`: login, registro y logout.
- `HomeController`: pagina de inicio y resumen general.
- `IncidenciaController`: avisos de clientes particulares.
- `AdminController`: panel administrador, tecnicos, incidencias, calendario, gestoras, zonas, comunidades y liquidaciones.
- `GestoraPanelController`: panel de gestora, creacion de avisos y consulta de comisiones.
- `TecnicoPanelController`: agenda del tecnico y finalizacion de servicios.
- `ZonasController`: endpoint JSON de servicios por zona.

## Modelos principales

- `Usuario`: usuarios autenticables y roles.
- `Incidencia`: servicios o avisos de reparacion.
- `Tecnico`: tecnicos asignables.
- `Especialidad`: tipos de servicio y precios.
- `Gestora`: empresas administradoras de fincas.
- `Comunidad`: comunidades gestionadas por gestoras.
- `Zona`: zonas de la ciudad.
- `Liquidacion`: estructura prevista para liquidaciones de gestoras.

## Roles y middlewares

El proyecto usa middlewares para proteger zonas de la aplicacion:

- `admin`: restringe rutas de administracion.
- `tecnico`: restringe la agenda del tecnico.
- `gestora`: restringe el panel B2B de gestoras.

Estos alias se registran en `bootstrap/app.php` y se aplican en `routes/web.php`.

## Flujo de ejemplo

Panel de gestora:

```text
/gestora
```

1. La ruta se define en `routes/web.php`.
2. Laravel comprueba que el usuario esta autenticado y tiene rol `gestora`.
3. `GestoraPanelController@dashboard` obtiene la gestora asociada al usuario.
4. Eloquent carga comunidades, zonas, incidencias, precios y comisiones.
5. La vista `resources/views/gestora/dashboard.blade.php` muestra el panel.

API por zonas:

```text
/api/servicios/zonas
```

1. La ruta se define en `routes/api.php`.
2. `ZonasController@incidenciasPorZona` calcula servicios finalizados por zona.
3. Laravel devuelve una respuesta JSON para ser consumida por WordPress en un producto posterior.

## Git y ramas

La rama base de trabajo del Producto 3 es:

```text
producto3-base
```

Ramas usadas durante el desarrollo:

- `p3-adrian`: migracion inicial y funcionalidad B2B.
- `p3-marc`: API REST y rutas API.
- `Antonio-p3`: correcciones de calendario, gestora, agenda y liquidaciones.
- `p3-jacobo`: revision, pruebas y documentacion.

Antes de empezar a trabajar conviene actualizar:

```bash
git fetch --all --prune
git checkout producto3-base
git pull origin producto3-base
git checkout p3-jacobo
git merge producto3-base
```

## Comandos utiles

Ver estado de la rama:

```bash
git status --short --branch
```

Ver rutas registradas por Laravel:

```bash
php artisan route:list
```

Recrear base de datos local:

```bash
php artisan migrate:fresh --seed
```

Arrancar servidor local:

```bash
php artisan serve
```

## Notas de revision

- La API `/api/servicios/zonas` responde correctamente, pero necesita servicios en estado `Finalizada` para mostrar porcentajes distintos de cero.
- Las liquidaciones tambien dependen de incidencias de gestora en estado `Finalizada`.
- El despliegue final debera comprobar la configuracion de base de datos en AWS y la ruta requerida `dominio.com/producto3`.
