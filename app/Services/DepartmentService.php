<?php

namespace App\Services;
use App\Imports\DepartmentsImport;
use App\Services\FileService;
use App\Models\Department;
 
class DepartmentService extends BaseService
{
    protected $model;
    protected $importClass = DepartmentsImport::class;       
    protected $fileService = FileService::class; 
        
    public function __construct(Department $model){$this->model = $model;} 
    
    public function import($request) {return $this->fileService::importExcel($request, $this->importClass,);}     
}  
  