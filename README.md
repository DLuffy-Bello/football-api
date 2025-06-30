# Laravel Football App

Una aplicación Laravel con autenticación API usando Laravel Passport y sistema de permisos con Spatie Laravel Permission.

## 🚀 Requisitos del Sistema

- PHP 8.2 o superior
- Composer
- MySQL/PostgreSQL/SQLite
- Node.js y npm (para assets front-end, si aplica)

## 🛠️ Tecnologías Principales

- **Laravel 12.0** - Framework PHP
- **Laravel Passport 13.0** - Autenticación API OAuth2
- **Spatie Laravel Permission 6.20** - Sistema de roles y permisos
- **Pest 3.8** - Framework de testing

## 🎯 Análisis y Enfoque de la Solución

### Problema Planteado
Se requería desarrollar un sistema de autenticación robusto para una aplicación de fútbol que permitiera:
- Registro e inicio de sesión de usuarios
- Manejo seguro de tokens de acceso
- Sistema de roles y permisos escalable
- Testing comprehensivo del sistema

### Análisis de Herramientas y Decisiones

#### 🔐 **Autenticación API: Laravel Passport vs Sanctum**

**¿Por qué Laravel Passport?**
- **OAuth2 completo**: Ofrece un servidor OAuth2 completo con soporte para diferentes tipos de grants
- **Tokens seguros**: Genera tokens JWT seguros con claves RSA
- **Escalabilidad**: Ideal para aplicaciones que pueden necesitar integración con terceros
- **Revocación de tokens**: Manejo robusto de invalidación de sesiones

**Alternativas consideradas:**
- Laravel Sanctum: Más simple pero limitado para casos complejos
- JWT-Auth: Requiere configuración manual adicional

#### 👥 **Sistema de Permisos: Spatie Laravel Permission**

**¿Por qué Spatie Permission?**
- **Flexibilidad**: Soporte para roles y permisos granulares
- **Compatibilidad**: Integración nativa con guards de Laravel
- **Performance**: Optimizado para consultas eficientes
- **Comunidad**: Ampliamente adoptado y mantenido

**Alternativas consideradas:**
- Sistema custom: Más trabajo de desarrollo y mantenimiento
- Laratrust: Menos documentación y comunidad

#### 🧪 **Testing: Pest vs PHPUnit**

**¿Por qué Pest?**
- **Sintaxis moderna**: Más legible y expresiva
- **Menos boilerplate**: Reduce código repetitivo
- **Better DX**: Mejor experiencia de desarrollo
- **Compatibilidad**: Construido sobre PHPUnit

### Arquitectura de la Solución

#### 🏗️ **Estructura del Controlador**
```php
// Patrón de manejo de excepciones unificado
try {
    // Lógica de negocio
} catch (\Exception $e) {
    // Respuesta consistente de error
}
```

#### 🛡️ **Validación con Form Requests**
- Separación de responsabilidades
- Validaciones reutilizables
- Mensajes de error consistentes

#### 🔄 **Manejo de Estados**
- Tokens con expiración automática
- Roles asignados automáticamente en registro
- Email verification por defecto

### Mejoras Implementadas

#### ✨ **Seguridad**
- Hash automático de contraseñas
- Verificación de email en registro
- Revocación segura de tokens
- Validación robusta de inputs

#### 📊 **Testing Comprehensive**
- Tests de integración completos
- Cobertura de casos edge
- Mocking responsable para excepciones
- Tests de flujos completos (registro → login → logout)

#### 🚀 **Performance**
- `DatabaseTransactions` para tests más rápidos
- Roles creados solo cuando es necesario
- Configuración optimizada de Passport

#### 🔧 **Mantenibilidad**
- Código autodocumentado
- Separación clara de responsabilidades
- Configuración centralizada
- Logging y debugging mejorados

## 📦 Instalación

### 1. Clonar el Repositorio

```bash
git clone <url-del-repositorio>
cd football-app
```

### 2. Instalar Dependencias

```bash
composer install
```

### 3. Configuración del Entorno

```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 4. Configurar Base de Datos

Edita el archivo `.env` con tus credenciales de base de datos:

```env
# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=football_app
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# API Externa de Fútbol
FOOTBALL_API_KEY=tu_api_key_aqui
FOOTBALL_API_URL=https://api.football-data.org/v4
```

> **Nota**: Para obtener las credenciales de la API de fútbol:
> 1. Regístrate en [Football Data API](https://www.football-data.org/client/register)
> 2. Obtén tu API key gratuita
> 3. Agrega las credenciales a tu archivo `.env`

### 5. Configurar Laravel Passport

```bash
# Instalar Passport (genera claves de encriptación y clientes OAuth2)
php artisan passport:install
```

### 6. Ejecutar Seeders

```bash
# Ejecutar seeders para poblar la base de datos con datos iniciales
php artisan db:seed
```

### 7. Iniciar la Aplicación

```bash
# Iniciar el servidor de desarrollo
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## 📡 API Endpoints

### 🔐 Autenticación

| Método | Endpoint | Descripción | Autenticación |
|--------|----------|-------------|---------------|
| POST | `/api/auth/register` | Registrar nuevo usuario | No |
| POST | `/api/auth/login` | Iniciar sesión | No |
| POST | `/api/auth/logout` | Cerrar sesión | Sí |

### ⚽ Competiciones

| Método | Endpoint | Descripción | Autenticación |
|--------|----------|-------------|---------------|
| GET | `/api/competitions` | Obtener todas las competiciones | Sí |
| GET | `/api/competitions/{id}` | Obtener competición específica | Sí |

### 🏆 Equipos

| Método | Endpoint | Descripción | Autenticación |
|--------|----------|-------------|---------------|
| GET | `/api/teams` | Obtener todos los equipos | Sí |
| GET | `/api/teams/{id}` | Obtener equipo específico | Sí |

### 👤 Jugadores

| Método | Endpoint | Descripción | Autenticación |
|--------|----------|-------------|---------------|
| GET | `/api/players` | Obtener todos los jugadores | Sí |
| GET | `/api/players/{id}` | Obtener jugador específico | Sí |

### 📝 Ejemplos de Uso

#### Registro de Usuario

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### Iniciar Sesión

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "juan@example.com",
    "password": "password123"
  }'
```

#### Cerrar Sesión

```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json"
```

#### Obtener Competiciones

```bash
curl -X GET http://localhost:8000/api/competitions \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json"
```

#### Obtener Competición Específica

```bash
curl -X GET http://localhost:8000/api/competitions/2021 \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json"
```

#### Obtener Equipos

```bash
curl -X GET http://localhost:8000/api/teams \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json"
```

#### Obtener Equipo Específico

```bash
curl -X GET http://localhost:8000/api/teams/86 \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json"
```

#### Obtener Jugadores

```bash
curl -X GET http://localhost:8000/api/players \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json"
```

#### Obtener Jugador Específico

```bash
curl -X GET http://localhost:8000/api/players/44 \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json"
```

## 🧪 Testing

### Ejecutar Tests

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar solo tests de autenticación
php artisan test --filter=AuthController

# Usar Pest directamente
./vendor/bin/pest

# Ejecutar tests específicos
./vendor/bin/pest tests/Feature/AuthControllerTest.php
```

### ✅ Cobertura de Tests

Los tests incluyen:

- ✅ Registro de usuarios exitoso
- ✅ Validación de campos requeridos
- ✅ Validación de formato de email
- ✅ Confirmación de contraseña
- ✅ Prevención de emails duplicados
- ✅ Login con credenciales válidas
- ✅ Rechazo de credenciales inválidas
- ✅ Logout exitoso
- ✅ Verificación de autenticación requerida
- ✅ Tests de integración completos

## 🔧 Desarrollo

### Iniciar Servidor de Desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

### 📁 Estructura de Archivos Importantes

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── CompetitionController.php
│   │   ├── TeamController.php
│   │   └── PlayerController.php
│   └── Requests/
│       ├── LoginRequest.php
│       └── RegisterRequest.php
├── Models/
│   └── User.php
tests/
├── Feature/
│   └── AuthControllerTest.php
routes/
└── api.php
```

### 🛠️ Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Ver rutas
php artisan route:list

# Generar documentación de API (si usas herramientas como Scribe)
php artisan scribe:generate

# Ejecutar migraciones frescas
php artisan migrate:fresh

# Ejecutar seeders
php artisan db:seed
```

## 🚨 Solución de Problemas Comunes

### Error: "no such table: roles"

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### Error: "Client authentication failed"

```bash
php artisan passport:install --force
```

### Tests fallan con errores de Mockery

Asegúrate de que tu `phpunit.xml` tenga la configuración correcta y que hayas ejecutado las migraciones para el entorno de testing.

### Error: "Class 'Laravel\Passport\PassportServiceProvider' not found"

```bash
composer dump-autoload
php artisan config:clear
```

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crea un Pull Request

## 📄 Licencia

Este proyecto está bajo la licencia MIT.

## 🆘 Soporte

Si encuentras algún problema o tienes preguntas, por favor:

1. Revisa la documentación de [Laravel](https://laravel.com/docs)
2. Consulta la documentación de [Laravel Passport](https://laravel.com/docs/passport)
3. Revisa la documentación de [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
4. Crea un issue en el repositorio del proyecto

---

⭐ **¡No olvides dar una estrella al proyecto si te fue útil!** ⭐
