<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Borrowing;
use Carbon\Carbon;

class NotificationService
{
    public function createDueDateReminder(Borrowing $borrowing)
    {
        $daysUntilDue = Carbon::now()->diffInDays($borrowing->due_date, false);
        
        if ($daysUntilDue <= 3 && $daysUntilDue > 0) {
            Notification::create([
                'user_id' => $borrowing->user_id,
                'type' => 'due_date',
                'title' => 'Book Due Soon',
                'message' => "Your book '{$borrowing->book->title}' is due in {$daysUntilDue} days. Please return it on time.",
                'link' => route('borrowings.index')
            ]);
        }
    }

    public function createOverdueNotification(Borrowing $borrowing)
    {
        $daysOverdue = Carbon::now()->diffInDays($borrowing->due_date);
        
        if ($daysOverdue > 0) {
            Notification::create([
                'user_id' => $borrowing->user_id,
                'type' => 'overdue',
                'title' => 'Book Overdue',
                'message' => "Your book '{$borrowing->book->title}' is {$daysOverdue} days overdue. Please return it immediately to avoid penalties.",
                'link' => route('borrowings.index')
            ]);
        }
    }

    public function createSystemAnnouncement($title, $message, $link = null)
    {
        // Create notification for all users
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'system',
                'title' => $title,
                'message' => $message,
                'link' => $link
            ]);
        }
    }

    public function createStatusUpdate(Borrowing $borrowing, $status)
    {
        $statusMessages = [
            'borrowed' => "You have successfully borrowed '{$borrowing->book->title}'.",
            'returned' => "You have successfully returned '{$borrowing->book->title}'.",
            'overdue' => "Your book '{$borrowing->book->title}' is now marked as overdue."
        ];

        Notification::create([
            'user_id' => $borrowing->user_id,
            'type' => 'status_update',
            'title' => 'Borrowing Status Update',
            'message' => $statusMessages[$status] ?? "Status updated for '{$borrowing->book->title}'.",
            'link' => route('borrowings.index')
        ]);
    }
} 