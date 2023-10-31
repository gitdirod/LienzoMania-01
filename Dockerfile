# Usa una imagen base de PHP
FROM php:8.2-fpm

# Instala las dependencias necesarias
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    fontconfig \
    libxrender1 \
    libjpeg62-turbo \
    xfonts-75dpi \
    xfonts-base 
# libjpeg-turbo8

# Descarga e instala wkhtmltox (ajusta la URL y el nombre del archivo según la versión que desees)


COPY libjpeg-turbo8_2.1.2-0ubuntu1_amd64.deb /tmp/
RUN dpkg -i /tmp/libjpeg-turbo8_2.1.2-0ubuntu1_amd64.deb

COPY wkhtmltox_0.12.6.1-2.jammy_amd64.deb /tmp/
RUN dpkg -i /tmp/wkhtmltox_0.12.6.1-2.jammy_amd64.deb

# Limpia la caché de paquetes y archivos temporales
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/*

# Define cualquier otro paso necesario para configurar tu entorno

# Establece el comando predeterminado para ejecutar cuando se inicie el contenedor
CMD ["php-fpm"]
