<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNotf extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine which channels the notification should be delivered on.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [
            'broadcast',
            // 'database',
            // 'mail',
        ];
    }

    /**
     * Broadcast representation.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'إشعار جديد',
            'message' => 'تم إرسال إشعار تجريبي بنجاح.',
            'type' => 'test',
            'time' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Database representation.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'إشعار جديد',
            'message' => 'تم إرسال إشعار تجريبي بنجاح.',
            'type' => 'test',
            'time' => now()->toDateTimeString(),
        ];
    }

    /**
     * Mail representation (اختياري).
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('إشعار جديد')
            ->line('تم إرسال إشعار تجريبي.')
            ->action('فتح الموقع', url('/'))
            ->line('شكراً لاستخدامك التطبيق.');
    }
}
