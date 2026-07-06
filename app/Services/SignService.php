<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Sign;
use App\Models\User;

class SignService extends BaseService
{
    protected $model;

    protected $fileService = new FileService::class; 

    public function __construct(Sign $model){$this->model = $model;} 
    
    public function index(){$signs = $this->model->paginate(10); $users = User::all(); return ['signs' => $signs,'users' => $users,];}
    
    public function prosessFile($request) {return $this->fileService->prosessFile($request);}     

    public function preViewFile($id){$this->fileService->preViewFile($id);}
}  
         