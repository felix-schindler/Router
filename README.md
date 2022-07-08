# Router

## Why use this?

- Dependency-free
- MVC
- No Models included, but you use PDO with the [Query class](/Backend/Core/Data/Query.php)
- Easy to use readable URLs like "/u/:name" instead of things like "user.php?name="

## Getting started

1. Clone GitHub Repo - `git clone git@github.com:felix-schindler/Router.git && cd Router`
2. Run `php -S localhost:8080` or throw it on a web server

## Requirements

- [PHP 8.1](https://www.php.net) with [PDO](https://www.php.net/manual/de/book.pdo.php)
- __OPTIONAL__: [Composer](https://getcomposer.org)

## Remove junk

This router is dependency-free. The only composer package installed is [PHPStan](https://phpstan.org), for static code analysis. Remove composer with

```zsh
rm -rf Backend/Libraries
rm .phpstan.neon
```

and remove the autoloader from the `index.php` file.

## Deploy

Make sure your web server:

- Supports PHP
- Serves files
- Routes through index.php (if the path doesn't match a file)

Works on: Apache, Nginx, [Caddy](https://caddyserver.com)
