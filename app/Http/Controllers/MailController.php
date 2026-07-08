<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Mail;
use App\Models\MailPrivacy;
use App\Models\Department;
use App\Services\MailService;
use App\Http\Requests\StoreMailRequest;
use App\Http\Requests\UpdateMailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Enums\RoleEnum as ROLE;
use Illuminate\Support\Facades\Auth;
class MailController extends Controller
{
    protected $service;
    protected $request;

    public function __construct(MailService $service, Request $request){$this->service = $service; $this->request = $request;}
    

  
    public function index(){$data = $this->service->index(); $mails = $data['mails']; $privacies = $data['privacies'];
    $entities = $data['entities']; $departments = $data['departments']; return view('mails.index', compact(['mails','privacies','entities','departments']));}
   

    public function store(StoreMailRequest $request){$this->service->store($request); return redirect()->route('mail.index');}


    public function update(UpdateMailRequest $request){$this->service->update($request->validated()['id'], $request->validated()); return redirect()->route('mail.index');}          


    public function share(){$request = $this->request; $this->service->share($request->id, $request); return redirect()->route('mail.index');}  


    public function destroy($id){$this->service->destroy($id); return redirect()->route('mail.index');}


    public function archive($id){$this->service->archive($id); return redirect()->route('mail.index');}


    public function destroyAll(Request $request){$this->service->deleteMultiple($request->ids); return redirect()->route('mail.index');}


    public function archiveAll(Request $request){$this->service->archiveMultiple($request->ids); return redirect()->route('mail.index');}


    public function preView($id){return $this->service->preViewFile($id);}  

  
    public function sign($id){$data = $this->service->editor($id); $file = $data['file']; $signatures = $data['signatures'];
    $fileUrl = $data['fileUrl']; return view('mails.sign', compact(['file','signatures','fileUrl']));}


    public function saveEditor(Request $request, $id){return $this->service->saveEditor($request, $id);}

    
    public function search(){$results = $this->service->search($this->request->search); return response()->json($results);}
}    