SISTEMA DE GESTIÓN MEDICA
- Para configurar el sitio, primero debemos cambiar el archivo inc.config.php que se encuentra en la carpeta CONFIG.
- cambiar user y pass (para obtener el usuario y clave encriptada, podemos colocar el siguiente código echo encrypt('usuario o clave',leer());
  y nos arroja el usuario y clave encriptados)
- ya configurado el usuario y clave, configuramos el sitio, en el archivo httpd.conf agregamos el siguiente alias:
  Alias /medivital /Library/WebServer/Documents/medivital/www
  <Directory /Library/WebServer/Documents/medivital/www/>
        Require all granted
  </Directory>
- En ambiente de desarrollo podemos asignar todos los permisos, ya en producción evitamos poner Require all granted.
#   d i g i m e d i c  
 