<?php

namespace App\Imports;
use App\Models\Entity;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterImport;
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Support\Facades\Cache;
use App\Notifications\ImportCompletedNotification;
    class EntitiesImport implements ToModel, WithHeadingRow, WithValidation
    {
    public function model(array $row)
    {
        return new Entity([
            'name' => $row['name'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:entities',

        ];
    }

    public function customValidationMessages()
    {
        return [    
        'name.required' => 'يجب ملأ حقل الاسم ',  
        'name.string' => 'الاسم جب ان يكون نص',  
        'name.unique' => 'الاسم موجود بالفعل',  
        
        ];
    }

}
