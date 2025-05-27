<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrowing;
use App\Services\NotificationService;
use Carbon\Carbon;

class CheckBorrowingStatus extends Command
{
    protected $signature = 'borrowings:check-status';
    protected $description = 'Check borrowing status and send notifications';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        // Check for due date reminders
        $activeBorrowings = Borrowing::with(['book', 'user'])
            ->whereNull('returned_at')
            ->get();

        foreach ($activeBorrowings as $borrowing) {
            // Check for due date reminders
            $this->notificationService->createDueDateReminder($borrowing);

            // Check for overdue books
            if (Carbon::now()->isAfter($borrowing->due_date)) {
                $this->notificationService->createOverdueNotification($borrowing);
                
                // Update status to overdue
                $borrowing->update(['status' => 'overdue']);
                $this->notificationService->createStatusUpdate($borrowing, 'overdue');
            }
        }

        $this->info('Borrowing status check completed.');
    }
} 