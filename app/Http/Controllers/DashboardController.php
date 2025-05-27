<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get real-time statistics
        $stats = [
            'total_books' => Book::count(),
            'total_users' => User::count(),
            'active_borrowings' => Borrowing::where('returned_at', null)->count(),
            'total_categories' => Category::count(),
        ];

        // Get recent borrowings with relationships
        $recent_borrowings = Borrowing::with(['user', 'book'])
            ->when(Auth::user()->role !== 'admin', function ($query) {
                return $query->where('user_id', Auth::id());
            })
            ->latest()
            ->take(5)
            ->get();

        // Get user's active borrowings
        $active_borrowings = Auth::user()->role === 'admin' 
            ? Borrowing::whereNull('returned_at')->count()
            : Borrowing::where('user_id', Auth::id())->whereNull('returned_at')->count();

        return view('dashboard', compact('stats', 'recent_borrowings', 'active_borrowings'));
    }
} 