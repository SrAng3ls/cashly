# Cashly 💙

Aplicación web de gestión financiera personal desarrollada con **PHP 8+, MySQL, MVC y MER**. Está pensada para el proyecto productivo escolar de Cashly y sigue la paleta azul de la referencia entregada.

## Funcionalidades
- Registro, inicio de sesión, cierre de sesión y recuperación de contraseña.
- Roles `usuario` y `admin`.
- Dashboard con balance, ingresos, gastos, gráfica de últimos 7 días y gráficos por categoría.
- CRUD de movimientos: ingreso/gasto, monto, categoría, método, fecha y descripción.
- CRUD de metas de ahorro + abonos + progreso + consejos + fecha límite opcional.
- CRUD de presupuestos semanales, mensuales o anuales + gastos asociados.
- Calendario mensual con transacciones y recordatorios.
- Alertas de próximas fechas de pago/suscripciones.
- Panel de administrador para consultar, crear, editar y eliminar clientes.
- SQL completo y diagrama MER en `docs/MER.md`.

## Requisitos
- PHP 8.1 o superior
- MySQL 8 / MariaDB 10.5+
- Apache (XAMPP recomendado) o servidor PHP integrado
- Navegador moderno
- Conexión a internet opcional para cargar Chart.js desde CDN

## Instalación rápida con XAMPP
1. Copia la carpeta `cashly` dentro de `htdocs`.
2. Crea una base de datos ejecutando `database/schema.sql` en phpMyAdmin.
3. Opcional: ejecuta `database/seed_admin.sql` para crear un administrador de prueba.
4. Revisa `app/config/config.php` y cambia usuario/contraseña de MySQL si es necesario.
5. Abre `http://localhost/cashly/`.

## Instalación con el servidor de PHP
Desde la carpeta del proyecto:
```bash
php -S localhost:8000
```
Luego abre `http://localhost:8000/`.

## Administrador de prueba
- Correo: `admin@cashly.local`
- Contraseña: `Admin123*`

**Cámbialos antes de una entrega real.**

## Recuperación de contraseña en entorno local
Por seguridad, Cashly genera un token de recuperación en lugar de revelar contraseñas. En desarrollo, si no hay correo configurado, la pantalla muestra el enlace de recuperación una vez generado. Para producción se debe conectar un servicio SMTP.

## Estructura MVC
- `app/models`: acceso y reglas de datos.
- `app/controllers`: lógica de cada módulo.
- `app/views`: interfaz.
- `app/core`: conexión, autenticación y controlador base.
- `index.php`: punto de entrada/router.
- `database/schema.sql`: modelo físico MySQL.
- `docs/MER.md`: modelo entidad-relación.
