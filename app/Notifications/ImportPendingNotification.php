<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage; 

class ImportPendingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'جاري معالجة استيراد الملف حالياً...',
            'action_url' => '/entities',
            'type' => 'pending'
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => 'بدأت عملية الاستيراد، سنخبرك فور الانتهاء.',
            'type' => 'info'
        ]);
    }
}