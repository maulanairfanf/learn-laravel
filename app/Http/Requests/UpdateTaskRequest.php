<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends StoreTaskRequest
{
    public function authorize(): bool
    {
        return true; // Sesuaikan dengan kebutuhan otorisasi Anda
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'priority_id' => 'sometimes|exists:priorities,id',
        ];
    }
}
