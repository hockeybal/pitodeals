<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'email', 'municipality_slug', 'municipality_name', 'deals', 'vacancies', 'street',
        'house_number', 'postal_code', 'city', 'consent', 'consented_at', 'status',
    ];

    protected function casts(): array
    {
        return [
            'deals' => 'boolean',
            'vacancies' => 'boolean',
            'consent' => 'boolean',
            'consented_at' => 'datetime',
        ];
    }
}
