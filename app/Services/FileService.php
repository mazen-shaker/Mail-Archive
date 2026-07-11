<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



  class FileService extends BaseService
{
   public function importExcel($request, $importClass){$request->validate(['file' => ['required','file','mimes:xlsx',],], ['file.required' => 'يرجى اختيار ملف.', 'file.file' => 'الملف المرفوع غير صالح.', 'file.mimes' => 'يجب أن يكون الملف بصيغة Excel (XLSX).',]); $import = new $importClass; return $import->import($request->file('file')->getPathname());}

   public function prosessFile($data,$model){$file = $data->file('file'); $path = $file->store('uploads', 'public'); $data = $data->toArray(); $data['file_path'] = $path; $data['user_id'] = Auth::id(); return $model->create($data);}

   public function previewFile($id,$model){$file = $model->find($id); return response()->file(storage_path('app/public/' . $file->file_path),['Content-Type' => Storage::mimeType($file->file_path), 'Content-Disposition' => 'inline; filename="'.$file->file_name.'"',]);}

}
