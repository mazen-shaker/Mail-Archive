<?php

namespace App\Services\Backup;

use App\Services\BaseService;
use App\Services\Backup\PackService as Pack;
use App\Models\BackUp;
use Illuminate\Support\Facades\Auth;
use App\Services\FileService;

class BackupService extends BaseService
{
    protected $model;
    protected $fileService;
    protected $packService;

    public function __construct(FileService $fileService, BackUp $model, Pack $packService)
    {
        $this->model = $model;
        $this->fileService = $fileService;
        $this->packService = $packService;
    }


    public function pack(){
    return $this->packService->create();
    }

    public function import($request)
    {
        return $this->fileService->importExcel($request, $this->importClass);
    }

    public function search($search, $archive)
    {
        $columns = ['name'];

        return $this->model->search($archive, $search, $columns);
    }
}
