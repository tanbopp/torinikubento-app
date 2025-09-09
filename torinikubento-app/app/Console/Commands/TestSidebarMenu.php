<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\MenuService;
use Illuminate\Console\Command;

class TestSidebarMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:sidebar-menu {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sidebar menu for different user roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $user = User::with('role')->find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found!");
                return;
            }
            $this->testUserMenu($user);
        } else {
            // Test all roles
            $this->info("Testing menu for all roles:");
            $this->info("================================");
            
            $users = User::with('role')->get();
            if ($users->isEmpty()) {
                $this->warn("No users found in database!");
                return;
            }

            foreach ($users->groupBy('role.name') as $roleName => $roleUsers) {
                $user = $roleUsers->first();
                $this->testUserMenu($user);
                $this->info(""); // Empty line
            }
        }
    }

    private function testUserMenu($user)
    {
        $roleName = $user->role ? $user->role->display_name : 'No Role';
        $permissions = $user->role ? $user->role->permissions : [];
        
        $this->info("User: {$user->name} | Role: {$roleName}");
        $this->info("Permissions: " . implode(', ', $permissions));
        $this->info("Menu Items:");
        
        $menuItems = MenuService::getFilteredMenu($user);
        
        if (empty($menuItems)) {
            $this->warn("  No menu items available for this user");
            return;
        }

        foreach ($menuItems as $item) {
            $this->line("  ├─ {$item['label']}");
            if (isset($item['submenu'])) {
                foreach ($item['submenu'] as $subItem) {
                    $this->line("  │  └─ {$subItem['label']}");
                }
            }
        }
        
        $this->info("--------------------------------");
    }
}
