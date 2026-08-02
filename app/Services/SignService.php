<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Sign;
use App\Models\User;
use App\Services\FileService;
use App\Services\CacheService;

class SignService extends BaseService
{
    protected $model;

    protected $fileService;
    protected $lowService;

    public function __construct(Sign $model,FileService $fileService,){$this->model = $model; $this->fileService = $fileService;}

    public function index(){$signs = CacheService::getCache('signs', $this->model); $users = User::all(); return ['signs' => $signs,'users' => $users,];}

    public function prosessFile($request) {return $this->fileService->prosessFile($request, $this->model);}

    public function preViewFile($id){$this->fileService->preViewFile($id);}

    public function search($search, $archive){$columns = ['name']; return $this->model->search($archive,$search,$columns);}
}

