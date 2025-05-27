<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache the statistics for 5 minutes
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_books' => Book::count(),
                'total_users' => User::count(),
                'active_borrowings' => Borrowing::whereNull('returned_at')->count(),
                'total_categories' => Category::count(),
            ];
        });

        // Get recent borrowings with eager loading
        $recent_borrowings = Borrowing::with(['user', 'book'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recent_borrowings' => $recent_borrowings
        ]);
    }
} 