# Dynamic Sidebar Menu System

Sistem sidebar dinamis yang dapat digunakan untuk semua role dengan menu yang berbeda-beda sesuai dengan permissions masing-masing role.

## Struktur File

### 1. MenuService (`app/Services/MenuService.php`)
Service yang berisi konfigurasi menu untuk setiap role dan logic untuk filtering berdasarkan permissions.

### 2. SidebarComposer (`app/View/Composers/SidebarComposer.php`)
View composer yang menyediakan data menu ke view sidebar.

### 3. Sidebar Blade Template (`resources/views/partials/sidebar/sidebar.blade.php`)
Template sidebar yang dinamis menggunakan data dari MenuService.

## Cara Penggunaan

### 1. Menambah Menu Baru
Edit file `app/Services/MenuService.php` dalam method `getMenuByRole()`:

```php
'new_role' => [
    [
        'label' => 'Menu Label',
        'route' => 'route.name',
        'icon' => 'fas fa-icon-name',
        'active_pattern' => 'route.*',
        'permission' => 'required_permission'
    ],
    // Menu dengan submenu
    [
        'label' => 'Parent Menu',
        'icon' => 'fas fa-parent-icon',
        'permission' => 'parent_permission',
        'submenu' => [
            [
                'label' => 'Sub Menu 1',
                'route' => 'sub.route1',
                'permission' => 'sub_permission1'
            ]
        ]
    ]
]
```

### 2. Menu dengan Separator
Untuk menambahkan garis pembatas:

```php
[
    'label' => 'Menu After Separator',
    'route' => 'menu.route',
    'separator' => true,  // Ini akan menambah garis di atas menu
    'permission' => 'menu_permission'
]
```

### 3. Menggunakan Blade Directives
Dalam template blade, Anda dapat menggunakan:

```blade
@hasPermission('manage_users')
    <!-- Content yang hanya ditampilkan jika user punya permission manage_users -->
@endhasPermission

@hasRole('owner')
    <!-- Content khusus owner -->
@endhasRole

@hasAnyPermission('permission1', 'permission2')
    <!-- Content jika user punya salah satu permission -->
@endhasAnyPermission
```

### 4. Testing Menu
Gunakan command artisan untuk testing:

```bash
# Test menu untuk semua role
php artisan test:sidebar-menu

# Test menu untuk user tertentu
php artisan test:sidebar-menu 1
```

## Konfigurasi Menu Role

### Owner
- Dashboard
- Keuangan & Laporan (Laporan Penjualan, Pengeluaran, Laba Rugi, Analitik)
- Manajemen (Karyawan, Menu & Harga, Promo, Supplier)
- Monitoring Live
- Pengaturan (Hak Akses, Sistem, Backup)

### Admin/Manager
- Dashboard
- Laporan Harian (Penjualan, Shift)
- Manajemen (Karyawan, Menu, Inventori)
- Shift Management

### Kasir
- POS
- Transaksi Hari Ini
- Pembayaran (Proses, History)

### Waiter/Pelayan
- Meja
- Pesanan (Buat Pesanan, Pesanan Aktif)
- Menu

### Kitchen Staff
- Kitchen Display
- Pesanan Masak
- Menu Items

### Inventory Staff
- Dashboard Stok
- Manajemen Stok (Update, Opname)
- Laporan Stok

### Supervisor
- Dashboard Supervisor
- Laporan Shift
- Persetujuan (Void, Refund)
- Summary Harian

## Cara Kerja

1. **User Login**: Sistem mengambil role dan permissions user
2. **MenuService**: Mengfilter menu berdasarkan permissions user
3. **SidebarComposer**: Menyediakan data menu yang sudah difilter ke view
4. **Sidebar Template**: Menampilkan menu secara dinamis

## Keamanan

- Semua menu item difilter berdasarkan permissions
- Menu yang tidak memiliki akses tidak akan ditampilkan
- Submenu kosong (tidak ada akses) akan menyembunyikan parent menu
- Route protection harus ditambahkan di level controller/middleware

## Maintenance

Untuk menambah role baru:
1. Tambah role di `Role::getRestaurantRoles()`
2. Tambah konfigurasi menu di `MenuService::getMenuByRole()`
3. Jalankan seeder untuk update database
4. Test dengan command `php artisan test:sidebar-menu`
