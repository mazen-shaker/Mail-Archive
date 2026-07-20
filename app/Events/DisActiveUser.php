<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class DisActiveUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $userId,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("private-App.Models.User.{$this->userId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.disactive';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'message' => 'تم تعطيل المستخدم',
        ];
    }
}
