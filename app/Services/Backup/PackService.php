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

class PackService
{
    public function create(): Backup
    {
        $createdBackup = null;

        $listener = function (BackupWasSuccessful $event) use (&$createdBackup): void {
            $createdBackup = $event;
        };

        Event::listen(BackupWasSuccessful::class, $listener);

        try {
            return DB::transaction(function () use (&$createdBackup) {
                $exitCode = Artisan::call('backup:run');

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
                    'name' => $createdBackup->backupName,
                    'file_path' => $this->resolveBackupPath($createdBackup),
                ]);

                Auth::user()->notify(
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
