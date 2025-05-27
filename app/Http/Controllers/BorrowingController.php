<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'borrow', 'return']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Borrowing::with(['book', 'user'])
            ->when(Auth::user()->role !== 'admin', function ($query) {
                return $query->where('user_id', Auth::id());
            })
            ->latest();

        $borrowings = $query->paginate(10);

        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::where('available_copies', '>', 0)->get();
        $users = User::where('role', 'user')->get();

        return view('borrowings.form', compact('books', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date|after:today',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->available_copies <= 0) {
            return back()->withErrors(['book_id' => 'This book is not available for borrowing.']);
        }

        $borrowing = Borrowing::create([
            'book_id' => $validated['book_id'],
            'user_id' => $validated['user_id'],
            'borrow_date' => now(),
            'due_date' => $validated['due_date'],
            'status' => 'borrowed'
        ]);

        $book->decrement('available_copies');

        return redirect()
            ->route('borrowings.index')
            ->with('success', 'Book has been borrowed successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function borrow(Book $book)
    {
        if ($book->available_copies <= 0) {
            return back()->withErrors(['error' => 'This book is not available for borrowing.']);
        }

        // Check if user already has an active borrowing of this book
        $activeBorrowing = Borrowing::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->whereNull('returned_at')
            ->exists();

        if ($activeBorrowing) {
            return back()->withErrors(['error' => 'You have already borrowed this book.']);
        }

        $borrowing = Borrowing::create([
            'book_id' => $book->id,
            'user_id' => Auth::id(),
            'borrow_date' => now(),
            'due_date' => now()->addDays(14),
            'status' => 'borrowed'
        ]);

        $book->decrement('available_copies');

        return redirect()
            ->route('borrowings.index')
            ->with('success', 'Book has been borrowed successfully. Please return it by ' . $borrowing->due_date->format('M d, Y'));
    }

    public function return(Borrowing $borrowing)
    {
        // Check if the user is authorized to return this book
        if (Auth::user()->role !== 'admin' && Auth::id() !== $borrowing->user_id) {
            return back()->with('error', 'You are not authorized to return this book.');
        }

        if ($borrowing->returned_at) {
            return back()->with('error', 'This book has already been returned.');
        }

        $borrowing->update([
            'returned_at' => now(),
            'status' => 'returned'
        ]);

        $borrowing->book->increment('available_copies');

        return redirect()
            ->route('borrowings.index')
            ->with('success', 'Book has been marked as returned.');
    }
}
