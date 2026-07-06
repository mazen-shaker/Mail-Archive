<?php

namespace App\Services;
use App\Models\MailDepartment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreMailRequest;
use App\Enums\MailStatusEnum;
use App\Models\Mail;
use App\Models\Sign;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;  


class MailService extends BaseService
{
    protected $model;
    protected $fileService = new FileService::class; 

    public function __construct(Mail $model){$this->model = $model;} 


    public function index(){$mails = $this->model->paginate(10); $privacies = MailPrivacy::all(); 
    $entities = Entity::all(); $departments = Department::all(); return ['mails' => $mails,'privacies' => $privacies,'entities' => $entities,'departments' => $departments];}
    
    
    public function preViewFile($id){$this->fileService->preViewFile($id);}


    public function store($request){$file = $request->file('file'); $path = $file->store('uploads', 'public'); $data = $request->toArray();
    $data['file_type'] = $file->getMimeType(); $data['file_path'] = $path; $data['writed_by'] = Auth::user()->name;
    $data['mail_status_id'] = MailStatusEnum::NOTPUBLISHED->value; return $this->model->create($data);}



    public function share($id, $data){$record = $this->model->findOrFail($id); $record->update(['trching' => $data->trching, 'privacy_id' => $data->privacy,]);
    $departments = collect($data->departments)->map(function ($deptId) use ($id) {
    return ['mail_id' => $id, 'department_id' => $deptId, 'created_at' => now(), 'updated_at' => now(),];})->toArray();
    MailDepartment::insert($departments); return $record;}



    public function editor($id){$file = $this->model->find($id); return ['file' => $this->model->findOrFail($id),
    'fileUrl' => Storage::url($file->file_path),'signatures' => Sign::where('user_id', auth()->id())->get(),];}



    public function saveEditor($request, $id) {$mail = Mail::findOrFail($id);
    if (!$request->hasFile('file')) {return response()->json(['status' => false, 'message' => 'لم يتم إرسال أي ملف'], 422);}
    if ($mail->file_path && Storage::disk('public')->exists($mail->file_path)) {Storage::disk('public')->delete($mail->file_path);}
    $newPath = $request->file('file')->store('uploads', 'public');
    $mail->update(['file_path' => $newPath, 'user_id'   => auth()->id(),]);
    return response()->json(['status' => true, 'message' => 'تم حفظ الملف بنجاح','url' => Storage::url($newPath),'redirect' => route('mail.index'),]);}
}
    
       