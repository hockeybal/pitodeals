<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc', 'max:190'],
            'municipality_slug' => ['required', 'string', 'max:120'],
            'municipality_name' => ['required', 'string', 'max:120'],
            'deals' => ['required', 'boolean'],
            'vacancies' => ['required', 'boolean'],
            'street' => ['required', 'string', 'max:160'],
            'house_number' => ['required', 'string', 'max:24'],
            'postal_code' => ['required', 'regex:/^[1-9][0-9]{3}\s?[A-Za-z]{2}$/'],
            'city' => ['required', 'string', 'max:120'],
            'consent' => ['accepted'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if (! $this->boolean('deals') && ! $this->boolean('vacancies')) {
                $validator->errors()->add('deals', 'Kies deals, vacatures of allebei.');
            }
        });
    }
}
