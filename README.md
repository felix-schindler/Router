# Router

Since I'm mostly using [SvelteKit](https://github.com/sveltejs/kit) and
[PocketBase](https://github.com/pocketbase/pocketbase), this Router is no longer
maintained. Maybe when I'm getting bored, I'll fix / improve one or two things.

## Why use this?

- Dependency-free
- MVC
- No Models included, but you use PDO with the
  [Query class](/Backend/Core/Data/Query.php)
- Easy to use readable URLs like `/u/:name` instead of things like
  `user.php?name=`

## Getting started

```bash
# Clone git repo
git clone git@github.com:felix-schindler/Router.git && cd Router
# Run dev server
php -S localhost:8080 -t src/
# or
composer run dev

# OPTIONAL - Install testing dependencies
composer install
# Run tests
composer run test:unit
# Static code analysis
composer run test:static
# Run both checks
composer run test
```

## Requirements

- [PHP 8.2](https://www.php.net) with
  [PDO](https://www.php.net/manual/de/book.pdo.php)
- **OPTIONAL**: [Composer](https://getcomposer.org)

### Remove junk

This router is dependency-free. The only composer packages installed are
[PHPStan](https://phpstan.org) and [PHPUnit](https://phpunit.de), for static
code analysis and unit testing. Remove with

```bash
# Remove composer things
rm -rf vendor/ composer* .phpstan.neon .vscode/tasks.json
# Remove docker things
rm docker-compose.yml Caddyfile
```

and remove the autoloader from the `index.php` file.

## Deploy

Make sure your web server:

- Supports PHP
- Serves files
- Routes through index.php (if the path doesn't match a file)

Tested on: Apache, Nginx, [Caddy](https://caddyserver.com)
