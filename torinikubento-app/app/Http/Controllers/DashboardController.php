<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard page (redirect based on role)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Redirect user to appropriate dashboard based on role
        switch ($user->role->name) {
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'kasir':
                return redirect()->route('pos.index');
            case 'waiter':
                return redirect()->route('waiter.tables');
            case 'kitchen':
                return redirect()->route('kitchen.orders');
            case 'inventory':
                return redirect()->route('inventory.index');
            case 'supervisor':
                return redirect()->route('supervisor.dashboard');
            default:
                return $this->generalDashboard();
        }
    }

    /**
     * Owner dashboard
     */
    public function owner()
    {
        $stats = [
            'total_menu' => 24,
            'total_orders_today' => 156,
            'total_customers_today' => 89,
            'revenue_today' => 12500000,
            'revenue_month' => 125000000,
            'total_staff' => 12,
            'active_tables' => 8,
        ];

        $popular_menus = [
            ['name' => 'Ayam Teriyaki Bento', 'orders' => 152, 'revenue' => 6800000],
            ['name' => 'Salmon Bento', 'orders' => 98, 'revenue' => 5390000],
            ['name' => 'Beef Bento', 'orders' => 76, 'revenue' => 4940000],
        ];

        $recent_orders = $this->getRecentOrders();

        return view('main.dashboard.owner.index', compact('stats', 'popular_menus', 'recent_orders'));
    }

    /**
     * Admin/Manager dashboard
     */
    public function admin()
    {
        $stats = [
            'orders_today' => 156,
            'orders_pending' => 12,
            'orders_completed' => 144,
            'revenue_today' => 12500000,
            'staff_on_duty' => 8,
            'tables_occupied' => 15,
        ];

        $staff_performance = [
            ['name' => 'John Doe', 'role' => 'Kasir', 'orders_served' => 45, 'performance' => 95],
            ['name' => 'Jane Smith', 'role' => 'Waiter', 'tables_served' => 12, 'performance' => 88],
            ['name' => 'Mike Wilson', 'role' => 'Kitchen', 'orders_prepared' => 78, 'performance' => 92],
        ];

        $recent_orders = $this->getRecentOrders();

        return view('dashboard.admin', compact('stats', 'staff_performance', 'recent_orders'));
    }

    /**
     * General dashboard for other roles
     */
    private function generalDashboard()
    {
        /** @var User $user */
        $user = Auth::user();
        $stats = [
            'role' => $user->getRoleName(),
            'last_login' => $user->last_login_at?->diffForHumans(),
            'active_sessions' => $user->getActiveSessions()->count(),
        ];

        $recent_activities = [
            ['action' => 'Logged in', 'time' => '2 minutes ago'],
            ['action' => 'Updated profile', 'time' => '1 hour ago'],
            ['action' => 'Changed password', 'time' => '2 days ago'],
        ];

        return view('dashboard.general', compact('stats', 'recent_activities'));
    }

    /**
     * Get recent orders (shared method)
     */
    private function getRecentOrders()
    {
        return [
            ['id' => '#TB001', 'customer' => 'John Doe', 'menu' => 'Ayam Teriyaki Bento', 'status' => 'Selesai', 'total' => 45000, 'time' => '2 menit lalu'],
            ['id' => '#TB002', 'customer' => 'Jane Smith', 'menu' => 'Salmon Bento', 'status' => 'Proses', 'total' => 55000, 'time' => '5 menit lalu'],
            ['id' => '#TB003', 'customer' => 'Mike Wilson', 'menu' => 'Beef Bento', 'status' => 'Diterima', 'total' => 65000, 'time' => '8 menit lalu'],
            ['id' => '#TB004', 'customer' => 'Alice Brown', 'menu' => 'Chicken Katsu Bento', 'status' => 'Selesai', 'total' => 48000, 'time' => '12 menit lalu'],
            ['id' => '#TB005', 'customer' => 'Bob Johnson', 'menu' => 'Vegetable Bento', 'status' => 'Proses', 'total' => 35000, 'time' => '15 menit lalu'],
        ];
    }
}
