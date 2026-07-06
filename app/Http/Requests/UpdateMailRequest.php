<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     */
    public function rules(): array
    {    

        return [    
            'title' => 'required|string',
            'description' => 'required|string',
            'entity_id' => 'required',
            'mail_privacy_id' => 'required',
            'id' => 'required',
        ];
    }
  
    public function messages(): array
    {
        return [
        'name.required' => 'يجب ملأ حقل الاسم ',  
        'name.string' => 'الاسم جب ان يكون نص',  
        'description.required' => 'يجب ملأ حقل الوصف ',  
        'description.string' => 'الوصف جب ان يكون نص',  
        'entity_id.required' => 'يجب تحديد الجهه المرسله',
        'mail_privacy_id.required' => 'يجب تحديد الخصوصيه',
        'id.required' => 'الطلب يحمل مشاكل امنيه' 
        ];
    }
}
