<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "required|max:255",
            "summary" => "required",
            "image" => "mimes:jpg,bmp,png",
            "stok" => "required",
            "category_id" => "required|exists:categories,id"
        ];
    }

    public function message(): array
    {
        return[
            "title.required" => "Title is required",
            "title.max" => "Title cannot contain more than 255 characters",
            "summary.required" => "Summary is required",
            "image.mimes" => "Image must be in JPG, BMP, or PNG format",
            "stok.required" => "Stok is required",
            "category_id.required" => "Category ID is required",
            "category_id.exists" => "Category ID is not found in Categories' Data"
        ];
    }
}
