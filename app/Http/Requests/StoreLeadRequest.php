<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'context_type' => ['required', 'in:deal,collective,fixed_costs'],
            'context_slug' => ['required', 'string', 'max:120'],
            'municipality_slug' => ['nullable', 'string', 'max:120'],
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'phone' => ['required', 'string', 'max:32'],
            'street' => ['required', 'string', 'max:160'],
            'house_number' => ['required', 'string', 'max:24'],
            'postal_code' => ['required', 'regex:/^[1-9][0-9]{3}\s?[A-Za-z]{2}$/'],
            'city' => ['required', 'string', 'max:120'],
            'consent' => ['accepted'],
            'marketing_consent' => ['sometimes', 'boolean'],
            'payload' => ['sometimes', 'array'],
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'voornaam',
            'last_name' => 'achternaam',
            'house_number' => 'huisnummer',
            'postal_code' => 'postcode',
            'consent' => 'toestemming',
        ];
    }
}
