<?php

namespace App\Console\Commands;

use App\Services\Backup\BackupRestoreService;
use Illuminate\Console\Command;
use Throwable;

class BackupRestoreCommand extends Command
{
    protected $signature = 'backup:restore
                            {backup : The backup path on the local disk}';

    protected $description = 'Restore the database from a backup';

    public function handle(
        BackupRestoreService $restoreService
    ): int {
        $backup = $this->argument('backup');

        if (! $this->confirm(
            "Are you sure you want to restore [{$backup}]?"
        )) {
            $this->warn('Restore cancelled.');

            return self::SUCCESS;
        }

        $this->info("Restoring [{$backup}]...");

        try {
            $restoreService->restore($backup);

            $this->info(
                'Database restored successfully.'
            );

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error(
                'Restore failed: ' . $exception->getMessage()
            );

            report($exception);

            return self::FAILURE;
        }
    }
}
