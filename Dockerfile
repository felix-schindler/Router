FROM dunglas/frankenphp:1-alpine

RUN install-php-extensions \
	pdo \
	pdo_mysql \
	json \
	mbstring \
	opcache

# Setup production environment
RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

# Set the working directory inside the container (could be any directory)
WORKDIR /app

# Copy all files to the working directory
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose the port 80
EXPOSE 80

# Start the server
CMD ["frankenphp", "run", "-c", "./Caddyfile"]
