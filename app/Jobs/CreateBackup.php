<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Backup\PackService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Notifications\BackUpCreateCompleteNotification;

class CreateBackup implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public array $userIds
    ) {}

    public function handle(PackService $packService): void
    {
        $packService->create();

        $users = User::whereIn('id', $this->userIds)->get();

        foreach ($users as $user) {
            $user->notify(
                new BackUpCreateCompleteNotification()
            );
        }
    }
}
