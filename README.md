# Javier baron
composer i <br>
cp .env.example .env <br>
php artisan jwt:secret <br>
php arisan migrate:fresh <br>
### Nota: El usuario o los usuarios se deben crear desde postman en autenticacion - register
### recordemos que debe ir una estructura como la siguiente
> basico
```
{
    "name": "Javier Ricardo Baron Fuentes",
    "email": "javierbaron6@gmail.com",
    "password": "Qwerty9601",
    "password_confirmation": "Qwerty9601",
    "rol": "administrador",
    "status": "activo"
}
```
> administrador
```
{
    "name": "Javier Ricardo Baron Fuentes",
    "email": "javierbaron6@gmail.com",
    "password": "Qwerty9601",
    "password_confirmation": "Qwerty9601",
    "rol": "basico",
    "status": "activo"
}
```
# Nos dirimos a ./postman/
aqui encontrara los endpoint <br>
