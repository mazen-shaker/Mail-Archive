<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {    

        return [    
            'name' => 'required|string',
            'id' => 'required',
            ];
    }
  
    public function messages(): array
    {
        return [
        'name.required' => 'يجب ملأ حقل الاسم ',  
        'name.string' => 'الاسم جب ان يكون نص',  
        'id.required' => 'الطلب يحمل مشاكل امنيه' 
        ];
    }
}
