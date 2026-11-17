# Pickleball Equipment Inventory

A Laravel 12 MVC application for managing pickleball equipment inventory. Administrators can sign in, manage equipment records, filter inventory reports, and download filtered PDF and Excel-compatible reports. The application does not manage rentals, sales, court reservations, or player transactions.

## Requirements

- PHP 8.2+
- Composer
- A Laravel-supported database (SQLite or MySQL)

## Setup

```powershell
composer install
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Configure the database connection in `.env` before migrating. The database seeder creates an administrator and eight example equipment records across all four categories, with both availability statuses represented.

**Demo login:** `admin@example.com` or `admin` / `password`. Change this password before using the application outside a local demonstration.

## Features

- Session login with email or username, login throttling, and secure logout.
- Authenticated equipment CRUD with server-side validation and creator relationships.
- Category and availability filters that can be combined or reset.
- PDF reports and Excel-compatible SpreadsheetML `.xls` downloads that use the same filters as the report page.
- Unit prices are displayed and exported in Philippine pesos (PHP).
- Validation errors and previously entered form values are displayed on create and edit forms.

## Tests

```powershell
php artisan test
```
