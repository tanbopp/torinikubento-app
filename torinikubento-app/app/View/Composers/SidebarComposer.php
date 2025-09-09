<?php

namespace App\View\Composers;

use App\Services\MenuService;
use Illuminate\View\View;

class SidebarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $user = auth()->user();
        $menuItems = MenuService::getFilteredMenu($user);
        
        $view->with([
            'menuItems' => $menuItems,
            'currentUser' => $user
        ]);
    }
}
