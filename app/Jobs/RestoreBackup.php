<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Artisan;
use App\Models\BackUp;
use App\Models\User;
use App\Services\Backup\BackupRestoreService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Notifications\BackUpRestoreCompleteNotification;

class RestoreBackup implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $id,
        public int $userId
    ) {}

    public function handle(BackupRestoreService $restore): void
    {
        $backupName = BackUp::where('id', $this->id)->value('name');

        if (!$backupName) {
            return;
        }

        $restore->restore($backupName . '.zip');

        Artisan::call('app:cache-warmup');

        $user = User::find($this->userId);

            $user->notify(
                new BackUpRestoreCompleteNotification()
            );
    }
}
