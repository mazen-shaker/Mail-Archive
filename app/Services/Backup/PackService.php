<?php

namespace App\Services\Backup;

use App\Models\BackUp;
use App\Notifications\BackUpCompleteNotification;
use Illuminate\Support\Facades\Artisan;
use RuntimeException;
use Spatie\Backup\Events\BackupWasSuccessful;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;


class PackService
{
public function create(): Backup
{
    $createdBackup = null;
    $filename = now()->format('Y-m-d-H-i');

    $listener = function (BackupWasSuccessful $event) use (&$createdBackup): void {
        $createdBackup = $event;
    };

    Event::listen(BackupWasSuccessful::class, $listener);

    try {
        return DB::transaction(function () use (&$createdBackup, $filename) {
            $exitCode = Artisan::call('backup:run', [
                '--filename' => $filename . '.zip',
            ]);

            if ($exitCode !== 0) {
                throw new RuntimeException(
                    'Backup command failed: ' . Artisan::output()
                );
            }

            if (!$createdBackup) {
                throw new RuntimeException(
                    'Backup command completed, but no successful backup event was received.'
                );
            }

            $backup = BackUp::create([
                'name' => $filename,
                'file_path' => $this->resolveBackupPath($createdBackup),
            ]);

Notification::send(
    Auth::user(),
    new BackUpCompleteNotification()
);


            return $backup;
        });

    } finally {
        Event::forget(
            BackupWasSuccessful::class,
            $listener
        );
    }
}
    private function resolveBackupPath(BackupWasSuccessful $event): string
    {
        $disk = $event->diskName;
        $name = $event->backupName;

        return $disk . '/' . $name;
    }
}
