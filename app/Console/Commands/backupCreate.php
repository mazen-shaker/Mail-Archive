<?php

namespace App\Console\Commands;

use App\Jobs\CreateBackup;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:backup-create')]
#[Description('Dispatch the backup creation job')]
class BackupCreate extends Command
{
    public function handle(): void
    {
        $userIds = User::where('role_id', RoleEnum::ADMIN->value)
            ->pluck('id')
            ->toArray();

        CreateBackup::dispatch($userIds);

        $this->info('Backup job dispatched successfully.');
    }
}
