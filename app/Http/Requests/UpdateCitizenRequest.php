<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCitizenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isAdminDesa() || auth()->user()->isKepala();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $citizen = $this->route('citizen');
        
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($citizen->id)
            ],
            'nik' => [
                'required',
                'string',
                'size:16',
                Rule::unique('users', 'nik')->ignore($citizen->id)
            ],
            'no_kk' => 'nullable|string|size:16',
            'birth_date' => 'required|date|before:today',
            'birth_place' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'religion' => 'required|in:islam,kristen,katolik,hindu,buddha,konghucu',
            'marital_status' => 'required|in:belum_kawin,kawin,cerai_hidup,cerai_mati',
            'occupation' => 'nullable|string|max:255',
            'education' => 'required|in:tidak_sekolah,sd,smp,sma,diploma,sarjana,magister,doktor',
            'address' => 'required|string',
            'rt_id' => 'required|exists:rts,id',
            'rw_id' => 'required|exists:rws,id',
            'phone' => 'nullable|string|max:20',
            'citizen_status' => 'required|in:active,moved,deceased,inactive',
            'password' => 'nullable|string|min:8',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan warga lain.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.before' => 'Tanggal lahir tidak valid.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'religion.required' => 'Agama wajib dipilih.',
            'marital_status.required' => 'Status perkawinan wajib dipilih.',
            'education.required' => 'Pendidikan wajib dipilih.',
            'address.required' => 'Alamat wajib diisi.',
            'rt_id.required' => 'RT wajib dipilih.',
            'rw_id.required' => 'RW wajib dipilih.',
            'citizen_status.required' => 'Status warga wajib dipilih.',
        ];
    }
}