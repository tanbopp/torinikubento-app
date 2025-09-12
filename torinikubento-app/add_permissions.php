<?php

require_once 'vendor/autoload.php';

// Initialize Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;

// Add missing permissions to roles
$roles = Role::whereIn('name', ['owner', 'admin'])->get();
$newPermissions = ['view_bom', 'view_pricing', 'view_profit_analysis'];

foreach($roles as $role) {
    $permissions = $role->permissions ?? [];
    foreach($newPermissions as $permission) {
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
        }
    }
    $role->update(['permissions' => $permissions]);
    echo "Updated {$role->name} role with new permissions\n";
}

echo "Done!\n";
