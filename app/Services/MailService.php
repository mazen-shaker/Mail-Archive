<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreMailRequest;
use App\Enums\MailStatusEnum;
use App\Models\Mail;
use App\Models\Sign;
use App\Models\MailDepartment;
use App\Services\FileService;
use App\Services\MailLowService;


class MailService extends BaseService
{
    protected $model;
    protected $fileService;
    protected $lowService;

    public function __construct(Mail $model, FileService $fileService, MailLowService $lowService){$this->model = $model;$this->fileService = $fileService; $this->lowService = $lowService;}


    public function index(){ return $this->lowService->index($this->model);}


    public function reportIndex(){ return $this->lowService->reportIndex($this->model);}


    public function store($request){$this->lowService->store($this->model,$request);}


    public function preViewFile($id){return $this->fileService->preViewFile($id, $this->model);}



    public function share($id, $data){$this->lowService->share($this->model,$id,$data);}



    public function editor($id){return $this->lowService->editor($this->model,$id);}



    public function saveEditor($request, $id){ return $this->lowService->saveEditor($this->model,$request,$id);}


    public function search($search, $archive){$columns = ['title','sign', 'description',  'entity.name', 'status.name']; $relations = ['entity','status']; return $this->model->search($archive,$search,$columns,$relations);}


    public function report($request){ return $this->lowService->report($this->model,$request);}

}



