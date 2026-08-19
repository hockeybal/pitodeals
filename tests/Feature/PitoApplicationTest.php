<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PitoApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_and_content_api_are_available(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('PITO — Ontdek je voordeel');

        $this->getJson('/api/content')
            ->assertOk()
            ->assertJsonStructure(['data' => ['settings', 'categories', 'offers', 'collectives', 'jobs', 'municipalities', 'links']]);
    }

    public function test_a_complete_update_subscription_is_stored(): void
    {
        $payload = [
            'email' => 'buur@example.nl',
            'municipality_slug' => 'woerden',
            'municipality_name' => 'Woerden',
            'deals' => true,
            'vacancies' => false,
            'street' => 'Voorbeeldstraat',
            'house_number' => '12A',
            'postal_code' => '3441 AB',
            'city' => 'Woerden',
            'consent' => true,
        ];

        $this->postJson('/api/subscriptions', $payload)->assertCreated();
        $this->assertDatabaseHas('subscriptions', ['email' => 'buur@example.nl', 'postal_code' => '3441 AB']);
    }

    public function test_an_address_and_preference_are_required_for_updates(): void
    {
        $this->postJson('/api/subscriptions', [
            'email' => 'buur@example.nl',
            'municipality_slug' => 'woerden',
            'municipality_name' => 'Woerden',
            'deals' => false,
            'vacancies' => false,
            'consent' => true,
        ])->assertUnprocessable()->assertJsonValidationErrors(['street', 'house_number', 'postal_code', 'city', 'deals']);
    }

    public function test_sitemap_only_contains_municipalities_with_content(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/gemeente/woerden', escape: false)
            ->assertDontSee('/gemeente/amsterdam', escape: false);
    }
}
