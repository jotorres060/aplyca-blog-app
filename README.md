## BlogAPP

BlogAPP es una aplicación web que permite a los usuarios registrarse, loguearse y comenzar a crear entradas atractivas y consultar lo que otros usuarios postean!

## Instalación

Para hacer uso de esta aplicación siga los siguientes pasos:

1. `git clone https://github.com/jotorres060/aplyca-blog-app.git`
2. `cd aplyca-blog-app`
3. `composer install`
4. Cree una base de datos, un usuario y una contraseña. Si lo desea puede crear los datos de la siguiente manera, tenga en cuenta que esto es solo para ambientes de prueba:
    - Base de datos => blog_app
    - Usuario => root
    - Password => 

    Si modifica los datos de acceso, recuerde actualizar su archivo `.env`
5. `php bin/console doctrine:schema:update --force`
6. Abra su navegador de preferencia y escriba la siguiente ruta:
   `http://localhost:8000`
