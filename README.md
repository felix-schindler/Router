# Router

MVC, rethought. File-based routing à la Next.js for PHP.

- Create Models yourself and fetch them using the `Database` class
- Define your controllers as `+controller.php` files
- Define your views as `+view.php` files

```
src/
├── routes/
│   ├── +controller.php
│   ├── +view.php
│   ├── +layout.php
│   └── ...
├── lib/
│   └── ...
└── index.php
```

## Why use this?

- Dependency-free
- MVC
- No Models included, but you can use the full power of PDO with the
  [Database class](/src/core/data/Database.php)
- Easy to use readable URLs like `/u/:name` instead of things like
  `user.php?name=`

## Requirements

- [PHP 8.3](https://www.php.net)
  - with pdo, json and mbstring extensions
- [Composer](https://getcomposer.org)

## Development

```bash
git clone git@github.com:felix-schindler/Router.git && cd Router
composer install
composer run dev
```
