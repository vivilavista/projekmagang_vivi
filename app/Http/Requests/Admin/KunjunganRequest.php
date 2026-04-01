<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class KunjunganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tamu_id'           => ['required', 'exists:tamu,id'],
            'tanggal_kunjungan' => ['required', 'date'],
            'tujuan_id'         => ['required', 'exists:master_tujuan,id'],
            'status'            => ['required', 'in:pending,selesai,batal'],
        ];
    }

    public function messages(): array
    {
        return [
            'tamu_id.required'           => 'Tamu wajib dipilih.',
            'tamu_id.exists'             => 'Tamu yang dipilih tidak valid.',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan wajib diisi.',
            'tanggal_kunjungan.date'     => 'Format tanggal tidak valid.',
            'tujuan_id.required'         => 'Tujuan kunjungan wajib dipilih.',
            'tujuan_id.exists'           => 'Tujuan yang dipilih tidak valid.',
            'status.required'            => 'Status wajib dipilih.',
            'status.in'                  => 'Status harus salah satu dari: pending, selesai, batal.',
        ];
    }
}
