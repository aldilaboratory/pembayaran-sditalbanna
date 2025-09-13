<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDataAdminRequest extends FormRequest
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
        $userId = $this->route('id');

        return [
            'name'     => ['required','string','max:100'],
            'username' => [Rule::unique('users','username')->ignore($userId)],
            // password opsional saat edit
            'password' => ['nullable','string','min:8'],
            'role'     => ['required','in:admin,kepala_sekolah'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role wajib diisi.',
            'role.in' => 'Role tidak valid.',
        ];
    }
}
