<?php

namespace App\Services;
use App\Models\MailDepartment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreMailRequest;
use App\Enums\MailStatusEnum;
use App\Enums\MailPrivacyEnum;
use App\Models\Mail;
use App\Models\MailPrivacy;
use App\Models\Inbox;
use App\Models\MailOwner;
use App\Models\Sign;
use App\Models\Entity;
use App\Models\MailStatus;
use App\Models\Department;
use App\Models\user;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\CacheService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResevedMailNotification as ResevingNotf;

class MailLowService
{

    public function index($model){$mails = $model->paginate(10); $privacies = CacheService::getCache('privacies', MailPrivacy::class);
    $entities = Entity::all(); $departments = Department::all(); return ['mails' => $mails,'privacies' => $privacies,'entities' => $entities,'departments' => $departments];}

    public function reportIndex($model){$mails = $model->get(); $archivedCount = $model::onlyTrashed()->count();
    $resultCount = $mails->count(); $sharedCount = $mails->where('mail_status_id', MailStatusEnum::PUBLISHED->value)->count(); $entities = Entity::all();
    $departments = Department::all(); $mailStatus = CacheService::getCache('mailStatus', MailStatus::class);return ['mails' => $mails,'statuses' => $mailStatus,'entities' => $entities,'departments' => $departments, 'resultCount'=>$resultCount, 'sharedCount'=>$sharedCount, 'archivedCount'=>$archivedCount,];}


    public function store($model,$request){$file = $request->file('file'); $path = $file->store('uploads', 'public'); $data = $request->toArray();
    $data['file_type'] = $file->getMimeType(); $data['file_path'] = $path;
    $data['mail_status_id'] = MailStatusEnum::NOTPUBLISHED->value; $mail = $model->create($data); MailOwner::create(["user_id" => Auth::user()->id, "mail_id" => $mail->id  ]); return $mail;}




    public function share($model,$id, $data){

    $record = $model->findOrFail($id);

    $snapshot = $record->replicate();

    $oldPath = $record->file_path;

    $extension = pathinfo($oldPath, PATHINFO_EXTENSION);

    $newPath = 'uploads/' . Str::uuid() . '.' . $extension;

    Storage::disk('public')->copy($oldPath, $newPath);

    $snapshot->file_path = $newPath;

    $snapshot->save();

    if($data->privacy == MailPrivacyEnum::PUBLIC->value){$departments = Department::pluck('id')->toArray();}else{$departments = $data->departments;};

    $insert = collect($departments)->map(function ($deptId) use ($snapshot) {
    return ['mail_id' => $snapshot->id, 'department_id' => $deptId, 'created_at' => now(), 'updated_at' => now(),];
    })->toArray();

    $record->mail_status_id = MailStatusEnum::PUBLISHED->value;

    $record->save();

    Inbox::insert($insert);

    User::whereIn('department_id', $departments)
    ->chunkById(500, function ($users) {
        Notification::send($users, new ResevingNotf());
    });

    return $snapshot;

    }



    public function editor($model,$id){$file = $model->find($id); return ['file' => $model->findOrFail($id),
    'fileUrl' => Storage::url($file->file_path),'signatures' => Sign::where('user_id', auth()->id())->get(),];}



    public function saveEditor($model,$request,$id) {
    $mail = $model->findOrFail($id);
    if (!$request->hasFile('file')) {return response()->json(['status' => false, 'message' => 'لم يتم إرسال أي ملف'], 422);}
    if ($mail->file_path && Storage::disk('public')->exists($mail->file_path)) {Storage::disk('public')->delete($mail->file_path);}
    $newPath = $request->file('file')->store('uploads', 'public');
    $mail->update(['file_path' => $newPath, 'sign'=> Auth::user()->name,]);
    return response()->json(['status' => true, 'message' => 'تم حفظ الملف بنجاح','url' => Storage::url($newPath),'redirect' => route('mail.index'),]);}


    public function report($model, $request){$dateFrom = $request->date_from;
    $dateTo = $request->date_to; $department = $request->department;
    $entity = $request->entity;$status = $request->status; $data = []; $query = $model::query();
    $query->when($dateFrom && empty($dateTo), function ($q) use ($dateFrom){$q->whereDate('created_at', '>=', $dateFrom);})
    ->when($dateTo && empty($dateFrom), function ($q) use ($dateTo){$q->whereDate('created_at', '<=', $dateTo);})->when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo){$q->whereBetween('created_at', [$dateFrom, $dateTo]);})
    ->when($department, function ($q) use ($department){$q->whereHas('inbox', function ($query) use ($department){$query->where('department_id', $department);});})
    ->when($entity, function ($q) use ($entity){$q->where('entity_id', $entity);})->when($status, function ($q) use ($status){$q->where('mail_status_id', $status);});
    $mails = $query->get(); $resultCount = $mails->count(); $sharedCount = $mails->where('mail_status_id', MailStatusEnum::PUBLISHED->value)->count();
    $archivedCount  = $model::onlyTrashed()->count();$entities = Entity::all(); $departments = Department::all(); $mailStatus = CacheService::getCache('mailStatus', MailStatus::class);return ['mails' => $mails,'statuses' => $mailStatus,'entities' => $entities,'departments' =>$departments, 'resultFromDate' =>$request->from_date, 'resultToDate'=>$request->to_date, 'resultDepartment'=>$request->$department, 'resultEntity'=>$request->entity, 'resultStatus'=>$request->status, 'resultCount'=>$resultCount, 'sharedCount'=>$sharedCount, 'archivedCount'=>$archivedCount,];}
}
