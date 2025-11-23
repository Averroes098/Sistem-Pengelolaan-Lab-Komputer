<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $userId = $this->route('user');
        return $user && $user->id === (int)$userId;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @phpstan-ignore-next-line */
        $authUser = Auth::user();
        $userId = $authUser?->id ?? $this->route('user');
        
        return [
            // Profile completion wajib
            'nim' => ['required', 'string', 'max:50', 'unique:users,nim,' . $userId],
            'no_telp' => ['required', 'string', 'max:16'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            
            // Opsional
            'program_studi' => ['nullable', 'string', 'max:255'],
            'angkatan' => ['nullable', 'string', 'max:4'],
            'alamat' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nim.required' => 'NIM wajib diisi',
            'nim.unique' => 'NIM sudah terdaftar',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
        ];
    }
}
