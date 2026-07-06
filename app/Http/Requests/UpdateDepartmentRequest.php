<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
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
            'name' => 'required|string|unique:departments',
            'code' => 'required|string|regex:/^[0-9]+$/|unique:departments,code',
            'id' => 'required',

        ];
    }
  
    public function messages(): array
    {
        return [
        'name.required' => 'يجب ملأ حقل الاسم ',  
        'name.string' => 'الاسم جب ان يكون نص',  
        'name.unique' => 'الاسم موجود بالفعل',  
        'code.required' => 'يجب ملأ حقل الكود ',  
        'code.string' => 'الكود جب ان يكون نص',  
        'code.unique' => 'الكود موجود بالفعل',
        'code.regex' => 'يجب أن يتكوّن الكود من أرقام فقط دون أن يحتوي على حروف أو رموز',
        'id.required' => 'الطلب يحمل مشاكل امنيه' 
        ];
    }
}
