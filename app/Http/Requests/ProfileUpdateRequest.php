<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Haal de validatieregels op die van toepassing zijn op het request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'birthday' => ['nullable', 'date', 'before_or_equal:today'],
            'about_me' => ['nullable', 'string', 'max:5000'], // Max 5000 tekens voor 'over mij'
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'], // Max 2MB voor profielfoto
            'remove_profile_photo' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Bepaal of de gebruiker gemachtigd is om dit request te maken.
     */
    public function authorize(): bool
    {
        return true; // Iedereen die ingelogd is mag zijn eigen profiel proberen bijwerken
    }
}
