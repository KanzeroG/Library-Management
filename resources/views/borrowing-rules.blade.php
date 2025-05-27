@extends('layouts.app')

@section('title', 'Borrowing Rules')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Borrowing Rules</h1>
                <p class="mt-2 text-sm text-gray-700">
                    Please read and follow these rules when borrowing books from our library.
                </p>
            </div>
        </div>

        <div class="mt-8 space-y-8">
            <!-- General Rules -->
            <div>
                <h2 class="text-lg font-medium text-gray-900">General Rules</h2>
                <div class="mt-4 space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <ul class="list-disc pl-5 space-y-2 text-sm text-gray-700">
                            <li>Books can be borrowed for a maximum of 14 days</li>
                            <li>Each user can borrow up to 3 books at a time</li>
                            <li>Books must be returned in good condition</li>
                            <li>Late returns will result in a temporary borrowing suspension</li>
                            <li>Lost or damaged books must be reported immediately</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- User Rules -->
            <div>
                <h2 class="text-lg font-medium text-gray-900">For Library Users</h2>
                <div class="mt-4 space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <ul class="list-disc pl-5 space-y-2 text-sm text-gray-700">
                            <li>You can request to borrow available books</li>
                            <li>Check your borrowing history in "My Borrowings"</li>
                            <li>Return books on or before the due date</li>
                            <li>Contact the library if you need to extend your borrowing period</li>
                            <li>Report any issues with borrowed books immediately</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Admin Rules -->
            @if(auth()->user()->isAdmin())
            <div>
                <h2 class="text-lg font-medium text-gray-900">For Administrators</h2>
                <div class="mt-4 space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <ul class="list-disc pl-5 space-y-2 text-sm text-gray-700">
                            <li>You can create borrowing records for any user</li>
                            <li>Monitor and manage all borrowing activities</li>
                            <li>Process book returns and handle overdue cases</li>
                            <li>Update book availability status</li>
                            <li>Generate borrowing reports and statistics</li>
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Penalties -->
            <div>
                <h2 class="text-lg font-medium text-gray-900">Penalties</h2>
                <div class="mt-4 space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <ul class="list-disc pl-5 space-y-2 text-sm text-gray-700">
                            <li>Late returns: 1-day suspension per day late</li>
                            <li>Damaged books: Replacement cost + 7-day suspension</li>
                            <li>Lost books: Replacement cost + 14-day suspension</li>
                            <li>Multiple violations may result in permanent borrowing privileges suspension</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 