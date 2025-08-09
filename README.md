# Voto

Sistema web para gestión de voto — sitio web: voto.ar

---

## Configuración de la base de datos

Para mantener seguros los datos sensibles, el archivo de conexión a la base de datos está separado en dos archivos:

- **conexion-sample.php**  
  Archivo de ejemplo que contiene la estructura básica y datos genéricos.  
  Debes copiar este archivo y renombrarlo a `conexion.php` para usarlo en tu entorno.

- **conexion.php**  
  Archivo que contiene las credenciales reales (usuario, contraseña, base de datos).  
  **Este archivo NO debe subirse al repositorio** y debe estar listado en `.gitignore`.

---

## Pasos para configurar

1. Copiar `conexion-sample.php` y renombrar la copia a `conexion.php`.  
2. Editar `conexion.php` con los datos reales de conexión a la base de datos.  
3. Subir el resto del proyecto normalmente, sin incluir `conexion.php`.  

---

## Importante

- No subir nunca el archivo `conexion.php` con datos reales a repositorios públicos.  
- Mantener el archivo `conexion.php` fuera del control de versiones gracias al `.gitignore`.  
- Esta práctica protege tus credenciales y mantiene tu proyecto seguro.

---

¡Gracias por usar y contribuir a Voto!  
