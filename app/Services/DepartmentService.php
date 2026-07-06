<?php

namespace App\Services;
use App\Imports\DepartmentsImport;
use App\Models\Department;
 
class DepartmentService extends BaseService
{

    protected $model;

    protected $importClass = DepartmentsImport::class; 
    public function __construct(Department $model)  
    {

      $this->model = $model;

    } 
    

        public function import($request) {   
        return $this->importExcel($request, $this->importClass,);
    }  

public function search($search, array $columns, $cachedData)  
{

   // $cachedData = Cache::get('entities_user_' . auth()->id());


    if (!$cachedData) {

        return $this->searchDB($search, $columns);
    }else{
    
    }

    $results = $cachedData->filter(function ($entity) use ($search, $columns) {
        $searchTerm = mb_strtolower($search);
        foreach ($columns as $col) {
            if (str_contains(mb_strtolower($entity->{$col}), $searchTerm)) {
                return true; 
            }
        }
        return false; 
    })->values();

    if ($results->isNotEmpty()) {
        return $results;
    }

    return $this->searchDB($search, $columns);
}

    
    public function searchDB($search, array $columns)
    {
        
         return $this->model::query()
         ->where(function ($q) use ($search,$columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%{$search}%");
            }
    })
     ->get();
        
   
    } 


}  
