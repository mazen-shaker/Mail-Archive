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
    public function __construct(Mail $model)    
    {
  
      $this->model = $model;

    } 


    public function storeMail($request)
    {
     
    $file = $request->file('file');
    $path = $file->store('uploads', 'public');

    $data = $request->toArray();

    $data['file_type'] = $file->getMimeType();
    $data['file_path'] = $path;
    $data['writed_by'] = Auth::user()->name;
    $data['mail_status_id'] = MailStatusEnum::NOTPUBLISHED->value;
    return $this->model->create($data);

    }


        public function share($id, $data)
    {
        $record = $this->model->findOrFail($id);
        $record->update([
            'trching' => $data->trching,
            'privacy_id' => $data->privacy,
            ]);
        $departments = collect($data->departments)->map(function ($deptId) use ($id) {
        return [
            'mail_id'       => $id,
            'department_id' => $deptId,
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    })->toArray();

    MailDepartment::insert($departments);      
        return $record;
    }


    public function editor($id)
    {       
      
    $file = $this->model->find($id);
    return [ 
     'file' => $this->model->findOrFail($id),
     'fileUrl' => Storage::url($file->file_path),     
     'signatures' => Sign::where('user_id', auth()->id())->get(),
    ];

    }


public function saveEditor($request, $id)
{
    $mail = Mail::findOrFail($id);

    if (!$request->hasFile('file')) {
        return response()->json([
            'status' => false,
            'message' => 'لم يتم إرسال أي ملف'
        ], 422);
    }

    // حذف الملف القديم
    if ($mail->file_path && Storage::disk('public')->exists($mail->file_path)) {
        Storage::disk('public')->delete($mail->file_path);
    }

    // حفظ الملف الجديد
    $newPath = $request->file('file')->store('uploads', 'public');

    // تحديث قاعدة البيانات
    $mail->update([
        'file_path' => $newPath,
        'user_id'   => auth()->id(),
    ]);

    return response()->json([
        'status' => true,
        'message' => 'تم حفظ الملف بنجاح',
        'url' => Storage::url($newPath),
        'redirect' => route('mail.index'),
    ]);
}

public function search($search, array $columns, $cachedData)
{


    if (!$cachedData) {  
        return $this->searchDB($search, $columns);
    }

    $searchTerm = mb_strtolower($search);

    $results = $cachedData->filter(function ($mail) use ($searchTerm, $columns) {

        foreach ($columns as $col) {

            $value = $this->resolveColumnValue($mail, $col);

            if ($value !== null && str_contains(mb_strtolower($value), $searchTerm)) {
                return true;
            }
        }

        return false;
    })->values();

    if ($results->isNotEmpty()) {
        
        $results->load(['user', 'department', 'entity', 'status', 'privacy']);
        
        return $results;
    }

        Log::info('my new results', [
        'results' => $results,
    ]);
    return $this->searchDB($search, $columns);
}

private function resolveColumnValue($mail, $column)
    {
        $segments = explode('.', $column);
        $currentEntity = $mail;

        foreach ($segments as $index => $segment) {
            if (is_null($currentEntity)) {
                return null;
            }

            if ($index < count($segments) - 1) {

                if (!$currentEntity->relationLoaded($segment)) {
                    return null; 
                }
            }

            $currentEntity = $currentEntity->{$segment} ?? null;
        }

        return is_scalar($currentEntity) ? $currentEntity : null;
    }
public function searchDB($search, array $columns)
{
    return $this->model::query()
        ->with(['user', 'department', 'entity', 'status', 'privacy'])     
        ->where(function ($q) use ($search, $columns) {
            
            foreach ($columns as $column) {
                if (!str_contains($column, '.')) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            }
            
            $q->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
            
            $q->orWhereHas('entity', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
            
            $q->orWhereHas('status', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
            
            $q->orWhereHas('privacy', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        })
        ->get();
}

}
    
       