# Use the official PHP image with Apache
FROM php:8.4-apache

# Install necessary PHP extensions (if needed)
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the content of the current directory to the container's /var/www/html directory
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Set the appropriate permissions for the web server
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 to the outside world
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
