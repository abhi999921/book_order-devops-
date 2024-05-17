# Use an existing PHP image as the base
FROM php:7.4-apache

# Update package lists and install necessary PHP extensions
RUN apt-get update && apt-get install -y \
    && docker-php-ext-install mysqli pdo_mysql

# Set working directory
#WORKDIR /var/www/html

# Copy your PHP files into the container
#COPY . /var/www/html

# The official PHP image automatically runs Apache in the foreground
# so there's no need to add a CMD or ENTRYPOINT instruction
