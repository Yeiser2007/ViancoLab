<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules()
    {
        $userId = $this->user ? $this->user->id : $this->route('user'); // Get user ID from route parameter if not available in $this->user

        return [
            'name' => 'sometimes|string|max:255',
            'first_name' => 'sometimes|string|max:50',
            'last_name' => 'sometimes|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'city' => 'nullable|string|max:50',
            'neighborhood' => 'nullable|string|max:50',
            'street' => 'nullable|string|max:50',
            'external_number' => 'nullable|string|max:10',
            'internal_number' => 'nullable|string|max:10',
            'postal_code' => 'nullable|string|max:10',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,other',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $userId,
            'mobile' => 'nullable|string|max:20|unique:users,mobile,' . $userId,
            'curp' => [
                'nullable',
                'string',
                'max:18',
                'unique:users,curp,' . $userId,
                'regex:/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9]{2}$/'
            ],
            'nationality' => 'nullable|string|max:50',
            'rfc' => [
                'nullable',
                'string',
                'max:13',
                'unique:users,rfc,' . $userId,
                'regex:/^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/'
            ],
            'profile_picture' => 'nullable|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $userId
            ],
            'password' => 'nullable|string|min:8',
            'is_active' => 'sometimes|boolean',
        ];
    }
    public function messages()
    {
        return [
            'curp.regex' => 'El CURP debe tener un formato válido',
            'rfc.regex' => 'El RFC debe tener un formato válido',
        ];
    }
}
