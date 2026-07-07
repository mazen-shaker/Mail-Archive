<?php

namespace App\Services;
use App\Imports\EntitiesImport;
use App\Models\Entity;
use App\Notifications\ImportPendingNotification;
use Illuminate\Support\Facades\Auth;
use App\Services\FileService;


class EntityService extends BaseService
{
    protected $model;
    protected $importClass = EntitiesImport::class; 
    protected $fileService; 

    public function __construct(FileService $fileService, Entity $model){$this->model = $model; $this->fileService = $fileService;}
  
    public function import($request) {return $this->fileService->importExcel($request, $this->importClass,);}     
}