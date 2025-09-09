<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page
     */
    public function index()
    {
        // Data untuk dashboard (nanti bisa diambil dari database)
        $stats = [
            'total_menu' => 24,
            'total_orders' => 156,
            'total_customers' => 89,
            'total_revenue' => 12500000,
        ];

        $popular_menus = [
            ['name' => 'Ayam Teriyaki Bento', 'orders' => 152, 'percentage' => 85],
            ['name' => 'Salmon Bento', 'orders' => 98, 'percentage' => 62],
            ['name' => 'Beef Bento', 'orders' => 76, 'percentage' => 48],
        ];

        $recent_orders = [
            ['id' => '#TB001', 'customer' => 'John Doe', 'menu' => 'Ayam Teriyaki Bento', 'status' => 'Selesai', 'total' => 45000, 'time' => '2 menit lalu'],
            ['id' => '#TB002', 'customer' => 'Jane Smith', 'menu' => 'Salmon Bento', 'status' => 'Proses', 'total' => 55000, 'time' => '5 menit lalu'],
            ['id' => '#TB003', 'customer' => 'Mike Wilson', 'menu' => 'Beef Bento', 'status' => 'Diterima', 'total' => 65000, 'time' => '8 menit lalu'],
        ];

        return view('main.dashboard.index', compact('stats', 'popular_menus', 'recent_orders'));
    }
}
