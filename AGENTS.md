# AGENTS.md — Coding Agent Guidelines

This file provides instructions for AI coding agents working in this repository.

---

## Project Overview

**Laravel 12 REST API** (PHP 8.2+). Primary stack:
- Backend: Laravel 12 + Sanctum
- Auth guards: `sanctum` (stateful SPA / token) and `api`
- Database: MySQL (production), SQLite in-memory (tests)
- UUID primary keys + SoftDeletes on all models

---

## Build, Lint & Test Commands

```bash
# Install PHP dependencies
composer install

# Full project setup (deps, key, migrate, npm, build)
composer setup

# Start all dev processes (serve + queue + logs + Vite)
composer dev

# Run ALL tests
composer test
# or
php artisan test

# Run a SINGLE test file
php artisan test tests/Feature/UserControllerTest.php

# Run a SINGLE test method
php artisan test --filter test_admin_can_create_user

# Run a specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Code style — fix in place
./vendor/bin/pint

# Code style — dry-run / lint check only
./vendor/bin/pint --test

# Build frontend assets
npm run build

# Start Vite dev server
npm run dev
```

No Makefile, no ESLint, no Prettier, no PHPStan config.  
`pint` uses Laravel's built-in default preset (PSR-12 + Laravel conventions); no `pint.json` exists.

---

## Before Writing or Moving Code — Consult Documentation

**Always fetch up-to-date Laravel 12 documentation via Context7 before implementing or refactoring any feature.**  
Use the `context7_resolve-library-id` + `context7_query-docs` tools with the relevant query (e.g. "Laravel 12 Form Requests validation", "Laravel 12 Eloquent relationships", "Laravel 12 Sanctum authentication").  
Do not rely solely on training-data knowledge for framework internals, as Laravel evolves rapidly.

---

## Architecture — Distributed / Layered Design

The codebase enforces a strict **separation of concerns**. Follow this pattern for every feature:

```
Route → Controller → Service → Model (Eloquent)
                  ↑
            FormRequest (validation)
```

### Controller (`app/Http/Controllers/`)
- **Thin controllers only.** Receive the request, call a service method, return a JSON response.
- No business logic, no Eloquent queries, no raw SQL.
- Always `use ApiResponseTrait` and return `$this->successResponse(...)`.
- Extend `App\Http\Controllers\Controller`.

```php
public function store(UserRequest $request): JsonResponse
{
    $this->user_service->createOrUpdateUser($request->validated());
    return $this->successResponse(null, 'User created successfully', 201);
}
```

### Service (`app/Services/`)
- All business logic lives here.
- Services are plain PHP classes — no interface required unless explicitly requested.
- Instantiated directly in the controller constructor: `$this->user_service = new UserService();`
- Return typed values (`void`, `array`, `LengthAwarePaginator`, `Collection`, etc.).
- Throw bare `\Exception` with an HTTP code: `throw new \Exception('Message', 404);`  
  The global handler in `bootstrap/app.php` will convert this to the correct JSON error response.

### FormRequest (`app/Http/Requests/`)
- All input validation must live in a dedicated `FormRequest` subclass — never validate inside controllers or services.
- **Always ask the user** before creating a new `FormRequest`:  
  > "Do you want me to create a FormRequest for this validation, or would you prefer inline rules?"
- Every `FormRequest` must implement:
  - `authorize(): bool` — return `true` (no per-request auth yet)
  - `rules(): array` — full rule set with type, length, uniqueness constraints
  - `messages(): array` — explicit English error messages for every rule

### Model (`app/Models/`)
- All models use `HasUuids`, `SoftDeletes`, `HasFactory`.
- Define `$fillable`, `$hidden`, and `casts()` (method form, Laravel 11+ style).
- Relations are typed: `HasMany`, `BelongsTo`, `BelongsToMany`, `HasOne` (fully imported).
- One-line relation methods are acceptable: `public function role(): BelongsTo { return $this->belongsTo(Role::class); }`
- Override `serializeDate()` to format dates as `'Y-m-d H:i:s'`.

---

## Code Style Guidelines

### PHP General
- **PHP 8.5+** — use modern syntax; avoid PHP 7 patterns.
- 4-space indentation, LF line endings, UTF-8, trailing newline (see `.editorconfig`).
- Run `./vendor/bin/pint` before committing to auto-fix formatting.

### Naming Conventions

| Element | Convention | Example |
|---|---|---|
| Classes, Interfaces, Traits | `PascalCase` | `UserController`, `ApiResponseTrait` |
| Methods | `camelCase` | `createOrUpdateUser()`, `showAll()` |
| Properties | `snake_case` | `$user_service`, `$role_id` |
| Local variables | `snake_case` | `$user_data`, `$allow_modules_id` |
| DB tables | `snake_case` plural | `users`, `permission_role` |
| DB columns | `snake_case` | `is_customer`, `role_id`, `deleted_at` |
| Route URIs | `kebab-case` | `/revoke-token`, `/modules/user` |
| Test methods | `snake_case` + `test_` prefix | `test_admin_can_delete_user` |
| Traits | `PascalCase` + `Trait` suffix | `ApiResponseTrait` |
| Permission names | `{module}.{action}` | `users.create`, `roles.mark_all` |

### Imports (`use` statements)
- One `use` per line immediately after the `namespace` declaration.
- No aliases unless a name collision exists.
- Import all Eloquent relation types explicitly (`HasMany`, `BelongsTo`, etc.).
- Laravel facades (`Auth`, `DB`, `Hash`, `Log`) are used directly — no aliasing.

### Return Types
- Declare return types on **all** public and protected methods.
- Controllers always return `: JsonResponse`.
- Services return specific types: `void`, `array`, `LengthAwarePaginator`, `Collection`.
- Model relations return the specific relation class.

### Type Hints
- Type-hint all method parameters.
- Avoid `mixed` unless truly necessary.
- `casts()` uses the method-form (Laravel 11+ pattern), not `$casts` property.

### Brace Style
- Opening brace for classes and methods goes on the **next line** (PSR-12).
- Pint enforces this automatically.

---

## Error Handling

- **No try/catch in controllers or services.** All exceptions bubble up.
- The global handler in `bootstrap/app.php` catches all exceptions and returns structured JSON.
- Services signal errors by throwing `\Exception` with an HTTP-appropriate code:
  ```php
  throw new \Exception('Resource not found', 404);
  throw new \Exception('Invalid credentials', 401);
  ```
- Validation errors are automatically handled by `FormRequest` → 422 JSON response.
- `QueryException` with SQLSTATE `23000` (integrity constraint) produces a user-friendly 500 message.
- Do not add custom exception classes unless the use case is clearly complex enough to warrant it.

---

## API Response Format

Use `ApiResponseTrait` in every controller. All responses follow this envelope:

```json
{ "code": 200, "status": "success", "message": "...", "data": { ... } }
{ "code": 422, "status": "error",   "message": "...", "errors": { ... } }
{ "code": 500, "status": "error",   "message": "...", "line": 42 }
```

Helper methods:
```php
$this->successResponse($data, 'Optional message', 200);
$this->errorResponse('Error message', 500);
```

---

## Routes

- All API routes are versioned under the `/api/v1/` prefix.
- Protected routes use the `auth:sanctum` middleware declared inline.
- Use `apiResource()` for standard CRUD; add custom routes separately.
- Route URIs are `kebab-case`.

---

## Testing

- **Feature tests** extend `Tests\TestCase` (database is SQLite in-memory; refreshed per suite).
- **Unit tests** extend `PHPUnit\Framework\TestCase` directly (no framework boot).
- Test method names: `snake_case` with `test_` prefix.
- Use `php artisan test --filter test_method_name` to run a single test.
- No test doubles/mocks are required for most service tests — prefer real Eloquent with factories.
- Factories exist for all 10 models in `database/factories/`.

---

## Migrations & Seeders

- Migration files: `{timestamp}_create_{table}_table.php` naming.
- All seeders use the `WithoutModelEvents` trait.
- Bulk inserts use `Model::insert([...])` (bypasses events; no auto-timestamps — set them explicitly).
- Single record creation uses `Model::create([...])`.
- Seeder run order is defined in `DatabaseSeeder`.

---

## Key Patterns Checklist

Before submitting any new feature:

- [ ] Controller is thin — delegates to a Service, returns `successResponse()`.
- [ ] Business logic is in a Service class.
- [ ] Input validation is in a FormRequest (or user was asked about it).
- [ ] Return types declared on all public methods.
- [ ] No try/catch blocks — exceptions bubble to global handler.
- [ ] New models use `HasUuids`, `SoftDeletes`, `HasFactory`.
- [ ] Context7 docs consulted for any Laravel 12 API being used.
- [ ] `./vendor/bin/pint` run before committing.
