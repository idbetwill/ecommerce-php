# 🚂 Configuración para Railway

## Variables de Entorno Requeridas

Configura estas variables en Railway Dashboard:

```
APP_ENV=prod
APP_DEBUG=false
APP_SECRET=tu-clave-secreta-muy-larga-y-segura-aqui
DATABASE_URL=postgresql://usuario:password@host:puerto/database
```

## Pasos para Desplegar en Railway

1. **Crear cuenta en Railway:**
   - Ve a [railway.app](https://railway.app)
   - Conecta tu cuenta de GitHub

2. **Crear nuevo proyecto:**
   - Click en "New Project"
   - Selecciona "Deploy from GitHub repo"
   - Elige este repositorio

3. **Configurar base de datos:**
   - Click en "New" → "Database" → "PostgreSQL"
   - Railway creará automáticamente las variables de entorno

4. **Configurar variables de entorno:**
   - Ve a "Variables" en tu proyecto
   - Agrega las variables listadas arriba
   - **IMPORTANTE:** Cambia `APP_SECRET` por una clave secreta única

5. **Deploy:**
   - Railway detectará automáticamente el Dockerfile
   - El deploy comenzará automáticamente

## Solución de Problemas

### Si Nixpacks falla:
- Railway debería usar el Dockerfile automáticamente
- Si no, ve a "Settings" → "Build" → "Use Dockerfile"

### Si el build falla:
- Verifica que todas las variables de entorno estén configuradas
- Revisa los logs en Railway Dashboard

### Si la app no inicia:
- Verifica que `DATABASE_URL` esté correctamente configurada
- Asegúrate de que la base de datos esté creada

## URLs de Acceso

Después del deploy exitoso:
- **Frontend:** https://tu-app.railway.app
- **Admin Productos:** https://tu-app.railway.app/admin/products
- **Admin Categorías:** https://tu-app.railway.app/admin/categories