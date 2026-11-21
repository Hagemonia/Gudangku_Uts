# Gudangku - Sistem Manajemen Gudang

UTS DT199 Backend Development, studi kasus 4. Laravel 13, Modular Monolith, Laravel Sanctum, RBAC (Admin dan Staff Gudang), Blade, SQLite.

## Menjalankan

```
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Akun demo (password: `password`):

| Role | Email |
| --- | --- |
| Admin | admin@gudang.test |
| Staff Gudang | staff@gudang.test |

Pengujian: `php artisan test`. Format kode: `./vendor/bin/pint`.

## Struktur modul

```
app/Modules/
  AuthModule       User, Role, login/register (session + token Sanctum), middleware role
  ProductModule    Barang, Kategori, Satuan, kontrak ProductCatalogContract
  SupplierModule   Pemasok, Termin, kontrak SupplierDirectoryContract
  InventoryModule  Stok Masuk, Stok Keluar, Kartu Stok, dashboard
app/Support/       BusinessException (dasar custom exception)
```

Setiap modul punya Service Provider sendiri (`loadMigrationsFrom`, `loadRoutesFrom`, `loadViewsFrom`). Komunikasi antar modul hanya lewat Contract; InventoryModule tidak pernah mengimpor model modul lain.

## Hak akses

| Fitur | Admin | Staff Gudang |
| --- | --- | --- |
| Lihat barang dan pemasok | ya | ya |
| CRUD barang, kategori, satuan, pemasok, termin | ya | tidak |
| Stok masuk, stok keluar, kartu stok | ya | ya |

## Endpoint token Sanctum

| Method | URL |
| --- | --- |
| POST | /api/auth/register |
| POST | /api/auth/login |
| GET | /api/auth/me |
| POST | /api/auth/logout |
"# Gudangku_Uts" 
"# Gudangku_Uts" 
