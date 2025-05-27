@extends('layouts.app')

@section('title', 'System Announcements')

@section('content')
<div class="bg-white dark:bg-dark-bg-secondary shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">System Announcements</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Manage system-wide announcements for all users.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('announcements.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                    Create Announcement
                </a>
            </div>
        </div>

        <div class="mt-8 space-y-4">
            @forelse($announcements as $announcement)
                <div class="bg-white dark:bg-dark-bg-secondary rounded-lg shadow">
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $announcement->title }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $announcement->message }}
                                </p>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    Sent {{ $announcement->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Are you sure you want to delete this announcement?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No announcements</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Get started by creating a new announcement.
                    </p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $announcements->links() }}
        </div>
    </div>
</div>
@endsection 