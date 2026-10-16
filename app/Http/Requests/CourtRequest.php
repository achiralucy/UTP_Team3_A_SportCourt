<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $court = $this->route('court');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('courts', 'name')->ignore($court?->id),
            ],
            'category' => ['required', Rule::in(['futsal', 'badminton'])],
            'price_per_hour' => ['required', 'integer', 'min:10000', 'max:5000000'],
            'status' => ['required', Rule::in(['available', 'maintenance'])],
            'facilities' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lapangan wajib diisi.',
            'name.max' => 'Nama lapangan maksimal 100 karakter.',
            'name.unique' => 'Nama lapangan sudah digunakan, gunakan nama lain.',
            'category.required' => 'Jenis olahraga wajib dipilih.',
            'category.in' => 'Jenis olahraga harus futsal atau badminton.',
            'price_per_hour.required' => 'Harga sewa per jam wajib diisi.',
            'price_per_hour.integer' => 'Harga sewa harus berupa angka bulat.',
            'price_per_hour.min' => 'Harga sewa minimal Rp 10.000.',
            'price_per_hour.max' => 'Harga sewa maksimal Rp 5.000.000.',
            'status.required' => 'Status operasional lapangan wajib dipilih.',
            'status.in' => 'Status lapangan tidak valid.',
            'facilities.max' => 'Fasilitas maksimal 255 karakter.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            'image.image' => 'File foto harus berupa gambar.',
            'image.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'image.max' => 'Ukuran foto maksimal 2 MB.',
            'image.uploaded' => 'Foto gagal diunggah, pastikan ukurannya tidak lebih dari 2 MB.',
        ];
    }
}