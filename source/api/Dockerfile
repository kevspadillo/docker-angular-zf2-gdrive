# Uploaer API Dockerfile

# We will be using PHP 7.1 image with apache installed
FROM php:7.1-apache

# Install dependencies, enable necessary apache modules and composer
RUN apt-get update \
	&& apt-get install -y git zlib1g-dev \
	&& docker-php-ext-install zip pdo pdo_mysql \
	&& a2enmod rewrite \
	&& a2enmod headers \
	&& curl -sS https://getcomposer.org/installer \
	  | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "file_uploads = On\n" \
         "memory_limit = 500M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

# Copy Apache vhost configuration 
COPY ./build_config/apache/000-upload-api.conf /etc/apache2/sites-available/000-upload-api.conf

# Set as enabled
RUN ln -s /etc/apache2/sites-available/000-upload-api.conf /etc/apache2/sites-enabled/000-upload-api.conf

# Set working directory
WORKDIR /var/www