<?php

namespace App\Services\Backup;

use App\Models\BackUp;
use App\Services\BaseService;
use App\Services\Backup\PackService as Pack;
use App\Services\Backup\BackupRestoreService as Restore;
use App\Services\FileService;
use App\Jobs\RestoreBackup;
use App\Jobs\CreateBackup;
use App\Notifications\BackUpRestorePendingNotification;
use App\Notifications\BackUpCreatePendingNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BackupService extends BaseService
{
    protected $model;
    protected $fileService;
    protected $packService;

    public function __construct(FileService $fileService, BackUp $model, Pack $packService, Restore $restore)
    {
        $this->model = $model;
        $this->fileService = $fileService;
        $this->packService = $packService;
        $this->restore = $restore;
    }

    public function index(){return $this->model->withTrashed()->paginate(10);}

public function pack()
{
    $user = Auth::user();

    $user->notify(
        new BackUpCreatePendingNotification()
    );

    $userId = [$user->id];

    CreateBackup::dispatch($userId);
}

    public function import($request)
    {
        return $this->fileService->importExcel($request, $this->importClass);
    }

public function packrestore($id)
{
    $user = Auth::user();

    $user->notify(
        new BackUpRestorePendingNotification()
    );

    RestoreBackup::dispatch($id, $user->id);
}

    public function update($id, $data){


        $record = $this->model->findOrFail($id);
        $old_name = $record->name;
        $new_name = (string)$data['name'];

    Storage::disk('local')->move(
    'Laravel/'.$old_name.'.zip',
    'Laravel/'.$new_name.'.zip',);

        $record->update($data);return $record;
    }

public function destroy($id)
{
    $record = $this->model->withTrashed()->findOrFail($id);

    Storage::disk('local')->delete(
        'Laravel/' . $record->name . '.zip'
    );

    return $record->forceDelete();
}

public function deleteMultiple($ids)
{
    $records = $this->model
        ->withTrashed()
        ->whereIn('id', $ids)
        ->get();

    foreach ($records as $record) {
        Storage::disk('local')->delete(
            'Laravel/' . $record->name . '.zip'
        );
    }

    return $this->model
        ->withTrashed()
        ->whereIn('id', $ids)
        ->forceDelete();
}
    public function search($search, $archive)
    {
        $columns = ['name'];

        return $this->model->search($archive, $search, $columns);
    }
}
