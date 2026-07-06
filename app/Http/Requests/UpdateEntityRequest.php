<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEntityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {    

        return [    
            'name' => 'required|string|unique:entities',
            'id' => 'required',
        ];
    }
  
    public function messages(): array
    {
        return [
        'name.required' => 'يجب ملأ حقل الاسم ',  
        'name.string' => 'الاسم جب ان يكون نص',  
        'name.unique' => 'الاسم موجود بالفعل', 
        'id.required' => 'الطلب يحمل مشاكل امنيه' 
        ];
    }
}
     