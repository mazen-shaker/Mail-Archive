<?php

namespace App\Imports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterImport;
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Support\Facades\Cache;
use App\Notifications\ImportCompletedNotification;
    class DepartmentsImport implements ToModel, WithHeadingRow, WithValidation
    {
    public function model(array $row)
    {
        return new Department([
            'name' => $row['name'],
            'code' => $row['code'],
        ]);   
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:departments',
            'code' => 'required|regex:/^[0-9]+$/|unique:departments,code',
        ];
    }

    public function customValidationMessages()
    {
        return [    
        'name.required' => 'يجب ملأ حقل الاسم ',  
        'name.string' => 'الاسم جب ان يكون نص',  
        'name.unique' => 'الاسم موجود بالفعل',  
        'code.required' => 'يجب ملأ حقل الكود ',  
        'code.unique' => 'الكود موجود بالفعل',
        'code.regex' => 'يجب أن يتكوّن الكود من أرقام فقط دون أن يحتوي على حروف أو رموز',
        
        ];
    }

}
