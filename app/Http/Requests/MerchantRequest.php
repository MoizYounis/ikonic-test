<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class MerchantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => "required|string|max:255",
            "email" => "required|string|email|unique:users,email",
            "api_key" => "required|min:8",
            "domain" => "required|regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i"
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            "domain.regex" => "Invalid Domain"
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        $data = [
            "name" => $request["name"],
            "email" => $request["email"],
            "password" => $request["api_key"],
            "domain" => $request["domain"],
        ];
        return $data;
    }
}
