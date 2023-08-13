<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if(request()->isMethod('post')){
            return [
                'name' => 'required|max:100',
                'detail' => 'required|max:200',
                'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'stock' => 'required|regex:/^\d+(\d{1,2})?$/',
                'discount' => 'required|regex:/^\d+(\d{1,2})?$/', 
            ];
        }else{
            return [
                'name' => 'required|max:100',
                'detail' => 'required|max:200',
                'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'stock' => 'required|regex:/^\d+(\d{1,2})?$/',
                'discount' => 'required|regex:/^\d+(\d{1,2})?$/', 
              ];
        }
        
    }

    public function messages(){
        if(request()->isMethod('post')){
            return [
              'name.required' => 'Name is required.',
              'description.required' => 'Description required.',
              'price.required' => 'Price is required.',
              'stock.required' => 'Stock is required.',
              'discount.required' => 'Discount is required.', 
            ];
        }else{
            return [
                'name.required' => 'Name is required.',
                'description.required' => 'Description required.',
                'price.required' => 'Price is required.',
                'stock.required' => 'Stock is required.',
                'discount.required' => 'Discount is required.', 
              ];
        }

    }
}
