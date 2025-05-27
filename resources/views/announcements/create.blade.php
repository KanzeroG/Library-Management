@extends('layouts.app')

@section('title', 'Create Announcement')

@section('content')
<div class="bg-white dark:bg-dark-bg-secondary shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Create Announcement</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Send a system-wide announcement to all users.
                </p>
            </div>
        </div>

        <form action="{{ route('announcements.store') }}" method="POST" class="mt-8 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                <div class="mt-1">
                    <input type="text" name="title" id="title" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-dark-bg-primary dark:border-gray-600 dark:text-white sm:text-sm"
                        placeholder="Enter announcement title">
                </div>
                @error('title')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                <div class="mt-1">
                    <textarea name="message" id="message" rows="4" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-dark-bg-primary dark:border-gray-600 dark:text-white sm:text-sm"
                        placeholder="Enter announcement message"></textarea>
                </div>
                @error('message')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('announcements.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-dark-bg-primary hover:bg-gray-50 dark:hover:bg-dark-bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Send Announcement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 