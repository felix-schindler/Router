# Router
An amazing dependency-free router in and for PHP so you can use readable routes like "/u/:username" instead of ugly ones like "/user.php?username=" and have an awesome MVC, object-oriented design pattern.
To see it in action, click [here](https://social.schindlerfelix.de).

## Getting started:
1. Clone GitHub Repo - `git clone git@github.com:felix-schindler/Router.git && cd Router`
2. __OPTIONAL__: Install composer dependencies - `cd Backend/Libraries && composer install`
3. That's it. Run `php -S localhost:8000` or throw it on a web server

This router is dependency-free but has composer support. The only composer package installed is [PHPStan](https://phpstan.org), for code checks.
If you do not need this, you can simply remove these files with the follwing commands:
```zsh
rm -rf Backend/Libraries
rm .phpstan.neon
```

## Requirements
- [PHP 8.0](https://www.php.net) with [PDO](https://www.php.net/manual/de/book.pdo.php)
- __OPTIONAL__: [Composer](https://getcomposer.org)

## Web servers

### Nginx
Your domain config needs a rewrite rule, something like this

```nginx
index.php;

location / {
  try_files $uri @rewrites;
}

location @rewrites {
  rewrite ^/(.*)$ /index.php?param=$1;
}

location ~ \.php$ {
  include snippets/fastcgi-php.conf;
  fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
}
```

### Apache2
Using Apache2 you'll need a .htaccess file looking something like this, to route everything through the `index.php` file, the main entry point.

```htaccess
RewriteEngine On
RewriteBase /
RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
RewriteRule ^(.*)$ https://%1/$1 [R=301,L]

Options -MultiViews

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
```
