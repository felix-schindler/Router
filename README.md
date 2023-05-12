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

## Requirements

- [PHP 8.2](https://www.php.net)
  - with pdo, json and mbstring extensions
- **OPTIONAL**: [Composer](https://getcomposer.org)

### Remove junk

```bash
# Remove composer things
rm -rf composer* vendor/ .phpstan.neon .vscode/tasks.json
# Remove docker things
rm docker-compose.yml Caddyfile
```

## Development

```bash
# Clone git repo
git clone git@github.com:felix-schindler/Router.git && cd Router
# Run dev server
php -S localhost:8080 -t src/
# or
composer run dev

# OPTIONAL - Install testing dependencies
composer i
# Run unit tests and static code analysis
composer run test
```

## Deploy

### Docker

```bash
docker compose up
```

### without Docker

Make sure your web server:

- Supports PHP
- Serves files
- Routes through index.php (if the path doesn't match a file)

Tested on: Apache, Nginx, [Caddy](https://caddyserver.com)
