<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreKunjunganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tamu_id' => ['required', 'exists:tamu,id'],
            'tujuan' => ['required', 'string', 'max:500'],
            'jam_masuk' => ['nullable', 'date'],
            'keterangan' => ['nullable', 'string'],
            'foto_wajah' => ['nullable', 'image', 'max:2048'],
            'instansi' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'tamu_id.required' => 'Tamu wajib dipilih.',
            'tamu_id.exists' => 'Tamu tidak ditemukan.',
            'tujuan.required' => 'Tujuan kunjungan wajib diisi.',
            'foto_wajah.image' => 'Foto wajah harus berupa gambar.',
            'foto_wajah.max' => 'Foto wajah maksimal 2MB.',
        ];
    }
}
