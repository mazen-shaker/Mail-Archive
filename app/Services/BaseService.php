<?php

namespace App\Services;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Entity;
use App\Models\Mail;
use App\Models\User;
use App\Models\Sign;
use App\Models\Role;
use App\Models\MailPrivacy;
use App\Models\Department;
use App\Models\UserStatus;

class BaseService
{
    protected $model;


    public function getCachedData(string $type)
{
    $userId = auth()->id();

    return Cache::tags([$type])->rememberForever($type . '_user_' . $userId, function () use ($type) {

        return match ($type) {
        'mails' => Mail::all()->toArray(),
        'privacies' => MailPrivacy::all()->toArray(),
        'departments' => Department::all()->toArray(),
        'entities' => Entity::all()->toArray(),
        'users' => User::all()->toArray(),
        'signs' => Sign::all()->toArray(),
        'roles' => Role::all()->toArray(),
        'usersStatuses' => UserStatus::all()->toArray(),
        default => [],
        };

    });
}

    public function importExcel($request, $importClass) {
     return Excel::import(new $importClass, $request->file('file'));
    }


    public function store($data)
    {
        return $this->model->create($data);
    }

    public function prosessFile($data)  
    {

       $file = $data->file('file');
       $path = $file->store('uploads', 'public');
       
       $data = $data->toArray();
       $data['file_path'] = $path;
       $data['user_id'] = Auth::id();

       return $this->model->create($data);
       return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);
        return $record;
    }


    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

    public function archive($id)
    {  
        $record = $this->model->findOrFail($id);
        return $record->delete();
    }

    public function deleteMultiple($ids)
    {
        return $this->model->whereIn('id', $ids)->forceDelete();
    }

    public function archiveMultiple($ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
    
    public function previewFile($id)
    {
    $file = $this->model->find($id);

    return response()->file(
        storage_path('app/public/' . $file->file_path),  
        [
            'Content-Type' => Storage::mimeType($file->file_path),
            'Content-Disposition' => 'inline; filename="'.$file->file_name.'"',
        ]
    );
    }

       
}
