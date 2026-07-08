<?php

namespace App\Services;
use App\Imports\DepartmentsImport;
use App\Services\FileService;
use App\Models\Department;
use App\Services\CacheService;
 
class DepartmentService extends BaseService
{
    protected $model;
    protected $importClass = DepartmentsImport::class;       
    protected $fileService; 
         
    public function __construct(FileService $fileService, Department $model ){$this->model = $model; $this->fileService = $fileService;} 
    
    public function index(){return CacheService::getCache('departments', $this->model);}

    public function import($request) {return $this->fileService->importExcel($request, $this->importClass,);}     

    public function search($search){$columns = ['name','code']; return $this->model->search($search,$columns);}
}  
   