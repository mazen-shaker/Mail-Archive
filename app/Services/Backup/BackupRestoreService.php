<?php

namespace App\Services\Backup;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use ZipArchive;

class BackupRestoreService
{
    public function restore(string $backupName): void
    {

        $backupName = 'Laravel/'.$backupName;

        $disk = Storage::disk('local');

        if (! $disk->exists($backupName)) {
            throw new RuntimeException(
                "Backup [{$backupName}] does not exist."
            );
        }

        $backupPath = $disk->path($backupName);

        $restoreDirectory = storage_path(
            'app/temp/restore-' . uniqid()
        );

        File::makeDirectory(
            $restoreDirectory,
            0755,
            true
        );

        try {
            $sqlPath = $this->extractSql(
                $backupPath,
                $restoreDirectory
            );

            $this->restoreDatabase($sqlPath);

        } finally {
            File::deleteDirectory($restoreDirectory);
        }
    }

    private function extractSql(
        string $backupPath,
        string $restoreDirectory
    ): string {
        $zip = new ZipArchive();

        if ($zip->open($backupPath) !== true) {
            throw new RuntimeException(
                'Unable to open backup archive.'
            );
        }

        $sqlFiles = [];

        for ($index = 0; $index < $zip->numFiles; $index++) {
            $name = $zip->getNameIndex($index);

            if (
                $name !== false &&
                str_ends_with(strtolower($name), '.sql')
            ) {
                $sqlFiles[] = $name;
            }
        }

        if (count($sqlFiles) !== 1) {
            $zip->close();

            throw new RuntimeException(
                'Backup must contain exactly one SQL dump.'
            );
        }

        $sqlFile = $sqlFiles[0];

        $zip->extractTo($restoreDirectory);
        $zip->close();

        $sqlPath = $restoreDirectory . '/' . $sqlFile;

        if (! File::isFile($sqlPath)) {
            throw new RuntimeException(
                'SQL dump was not extracted correctly.'
            );
        }

        return $sqlPath;
    }

    private function restoreDatabase(string $sqlPath): void
    {
        $database = config('database.connections.mysql');

        if (
            empty($database['host']) ||
            empty($database['port']) ||
            empty($database['username']) ||
            empty($database['database'])
        ) {
            throw new RuntimeException(
                'MySQL database configuration is incomplete.'
            );
        }

        $command = [
            'mysql',
            '--host=' . $database['host'],
            '--port=' . $database['port'],
            '--user=' . $database['username'],
            '--password=' . $database['password'],
            $database['database'],
        ];

        $result = Process::input(
            File::get($sqlPath)
        )
            ->timeout(300)
            ->run($command);

        if ($result->failed()) {
            throw new RuntimeException(
                'Database restore failed: ' .
                $result->errorOutput()
            );
        }

    }
}
