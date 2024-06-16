<div>
    <p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300"></a></p>
    <p align="center"><a href="https://livewire.laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/livewire/livewire/main/art/readme_logo.png" width="200"></a></p>
</div>

# Sistema de Reserva de espacios

## Instrucciones

### 1. Clonar o descargar el código mediante:

https
```
git clone https://github.com/AndreRO-11/SistemaReserva.git
```
ssh
```
git clone git@github.com:AndreRO-11/SistemaReserva.git
```

### 2. Ingresar al directorio de SistemaReserva
```
cd SistemaReserva/
```

### 3. Configurar el arvhico .env y base de datos
Mediante el siguiente comando, creamos un archivo .env con el contenido del archivo .env.example
```
cp .env.example .env
```
Verificamos que la conexión a nuestra bse de datos se encuentre en mysql
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Instalación de dependencias de php a través composer
```
composer install
```
Y luego
```
php artisan key:generate
```

### 5. Creación de las tablas y migraciones
```
php artisan migrate --seed
```

### 6. Instalación de dependencias JS a través de npm
```
npm install
```
```
npm run dev
```

### 7. Inicio del servidor.
```
php artisan serve
```

## Nota:
Este proyecto cuenta con seeders para poblar las tablas de hours, details y campus además de incorporar un usuario administrador que permitirá ingresar al sistema y registrar un nuevo usuario para luego desactivar manualmente este. Las credenciales de ingreso son las siguiente:

Usuario: admin@admin.com

Contraseña: admin

Cabe destacar que una vez desactivado un usuario, este no podrá ingresar al sistema hasta que su usuario sea habilitado por otro usuario dentro del sistema.
