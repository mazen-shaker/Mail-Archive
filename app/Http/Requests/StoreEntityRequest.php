<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEntityRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array    
    {    

        return [    
            'name' => 'required|string|unique:entities',
        ];
    }
  
    public function messages(): array
    {
        return [
        'name.required' => 'يجب ملأ حقل الاسم ',  
        'name.string' => 'الاسم جب ان يكون نص',  
        'name.unique' => 'الاسم موجود بالفعل',  
        ];
    }
}
  