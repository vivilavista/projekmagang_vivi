<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKunjunganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tujuan' => ['required', 'string', 'max:500'],
            'keterangan' => ['nullable', 'string'],
            'foto_wajah' => ['nullable', 'image', 'max:2048'],
            'instansi' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'tujuan.required' => 'Tujuan kunjungan wajib diisi.',
            'foto_wajah.image' => 'Foto wajah harus berupa gambar.',
            'foto_wajah.max' => 'Foto wajah maksimal 2MB.',
        ];
    }
}
