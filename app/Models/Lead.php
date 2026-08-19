<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'context_type', 'context_slug', 'municipality_slug', 'first_name', 'last_name',
        'email', 'phone', 'street', 'house_number', 'postal_code', 'city', 'consent',
        'marketing_consent', 'consented_at', 'status', 'source_url', 'payload',
    ];

    protected function casts(): array
    {
        return [
            'consent' => 'boolean',
            'marketing_consent' => 'boolean',
            'consented_at' => 'datetime',
            'payload' => 'array',
        ];
    }
}
