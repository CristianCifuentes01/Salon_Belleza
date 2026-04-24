# AppSalon - Sistema de Gestión de Belleza

AppSalon es una aplicación web robusta diseñada para la gestión de servicios y citas en un salón de belleza. Permite a los clientes reservar citas de forma sencilla y a los administradores gestionar el catálogo de servicios, usuarios y citas de manera eficiente.

## 🚀 Tecnologías Utilizadas

- **Backend:** [Laravel 12](https://laravel.com/) (PHP 8.2+)
- **Frontend:** Blade, [Tailwind CSS](https://tailwindcss.com/)
- **Base de Datos:** SQLite (Configuración por defecto)
- **Bundler:** [Vite](https://vitejs.dev/)
- **Autenticación:** [Laravel Breeze](https://laravel.com/docs/breeze)
- **Iconos:** Heroicons

## ✨ Funcionalidades Implementadas

### 👤 Usuarios y Autenticación
- **Registro e Inicio de Sesión:** Sistema seguro de autenticación con roles.
- **Gestión de Perfil:** Los usuarios pueden actualizar su información personal.
- **Roles:** Diferenciación entre Clientes y Administradores.

### 💇 Servicios
- **Catálogo Público:** Visualización de servicios disponibles para todos los usuarios.
- **Gestión Administrativa:** CRUD completo de servicios (crear, leer, actualizar, eliminar) con control de estado (activo/inactivo).

### 📅 Citas (Appointments)
- **Reserva de Citas:** Los clientes pueden seleccionar múltiples servicios y una fecha/hora.
- **Verificación de Disponibilidad:** Sistema dinámico para evitar solapamiento de citas.
- **Historial:** Los clientes pueden ver sus citas pasadas y futuras en su panel personal.

### 🛠️ Panel de Administración
- **Dashboard:** Resumen estadístico de citas y usuarios.
- **Gestión de Citas:** Los administradores pueden ver todas las citas y cambiar su estado (Pendiente, Completada, Cancelada).
- **Gestión de Usuarios:** CRUD administrativo para gestionar la base de datos de usuarios.
- **Reportes:** Generación y descarga de reportes de citas en formato CSV.

## 🛠️ Instalación y Configuración

Sigue estos pasos para ejecutar el proyecto localmente:

1. **Clonar el repositorio:**
   ```bash
   git clone <https://github.com/CristianCifuentes01/Salon_Belleza.git>
   
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Instalar dependencias de JavaScript:**
   ```bash
   npm install
   ```

4. **Configurar el entorno:**
   - Copia el archivo `.env.example` a `.env`:
     ```bash
     cp .env.example .env
     ```
   - Genera la clave de la aplicación:
     ```bash
     php artisan key:generate
     ```

5. **Configurar la base de datos:**
   - Por defecto, el proyecto usa SQLite. Crea el archivo de base de datos:
     ```bash
     touch database/database.sqlite
     ```
   - Ejecuta las migraciones y carga los datos de prueba (seeders):
     ```bash
     php artisan migrate --seed
     ```

6. **Compilar assets y ejecutar servidor:**
   - En una terminal, compila los estilos y scripts:
     ```bash
     npm run dev
     ```
   - En otra terminal, inicia el servidor de Laravel:
     ```bash
     php artisan serve
     ```

## 🔐 Credenciales de Prueba

Puedes usar las siguientes cuentas preconfiguradas para explorar la aplicación:

### Administrador
- **Email:** `admin@appsalon.com`
- **Password:** `password`

### Cliente
- **Email:** `cliente@appsalon.com`
- **Password:** `password`

---
Desarrollado por Catalina Estrada, Arley David Alpala, Cristian Cifuentes.
