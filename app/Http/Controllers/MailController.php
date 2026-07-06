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

    public function __construct(MailService $service, Request $request)
    {
        $this->service = $service;
    
        $this->request = $request;  
    }
    

  
    public function index()
    {
    
    $mails = Mail::hydrate($this->service->getCachedData('mails'));
    if(Auth::user()->role_id !== 1){ 
    
    $mails = Mail::hydrate($this->service->getCachedData('mails'))->filter(function ($mail) {
        return $mail->department_id == Auth::user()->department_id
            && $mail->department_id !== null || $mail->writed_by === Auth::user()->name;
    });}
    $privacies = MailPrivacy::hydrate($this->service->getCachedData('privacies'));
    $entities = Entity::hydrate($this->service->getCachedData('entities'));
    $departments = Department::hydrate($this->service->getCachedData('departments'));
    return view('mails.index', compact(['mails','privacies','entities','departments']));

    }
   
    public function store(StoreMailRequest $request)
    {
        $this->service->storeMail($request);
        Cache::tags(['mails'])->flush();
        return redirect()->route('mail.index');
    }


    public function update(UpdateMailRequest $request)
    {
        $this->service->update($request->validated()['id'], $request->validated());
        Cache::tags(['mails'])->flush();
        return redirect()->route('mail.index');
    }          

        public function share()
    {   
        $request = $this->request;
        $this->service->share($request->id, $request);
        Cache::tags(['mails'])->flush();
        return redirect()->route('mail.index');
    }  

    public function destroy($id)
    {
        $this->service->destroy($id);
        Cache::tags(['mails'])->flush();
        return redirect()->route('mail.index');
    }

    public function archive($id)
    {
        $this->service->archive($id);
        Cache::tags(['mails'])->flush();
        return redirect()->route('mail.index');
    }

    public function destroyAll(Request $request)
    {
        $this->service->deleteMultiple($request->ids);
        Cache::tags(['mails'])->flush();
        return redirect()->route('mail.index');
    }

    public function archiveAll(Request $request)
    {
        $this->service->archiveMultiple($request->ids);
        Cache::tags(['mails'])->flush();
        return redirect()->route('mail.index');
    }

        public function preView($id)
    {
       return $this->service->preViewFile($id);
    }


    public function sign($id)
    {
       $data = $this->service->editor($id);
       $file = $data['file'];
       $signatures = $data['signatures'];
       $fileUrl = $data['fileUrl'];
       return view('mails.sign', compact(['file','signatures','fileUrl']));

    }


        public function saveEditor(Request $request, $id)
    {
        return $this->service->saveEditor($request, $id);
         
    }
public function search()

{

$cachedData = Mail::hydrate($this->service->getCachedData('mails'));

$columns = ['title','description','writed_by','entity.name','user.name','status.name','privacy.name'];

$search = $this->request->search;

$results = $this->service->search($search,$columns,$cachedData);

return response()->json($results);

}


}    