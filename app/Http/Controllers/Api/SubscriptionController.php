<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $subscription = Subscription::updateOrCreate(
            ['email' => mb_strtolower($data['email']), 'municipality_slug' => $data['municipality_slug']],
            $data + ['consented_at' => now(), 'status' => 'active'],
        );

        return response()->json([
            'message' => 'Gelukt. Nieuwe kansen uit jouw omgeving komen voortaan naar je toe.',
            'data' => ['id' => $subscription->getKey(), 'status' => $subscription->status],
        ], $subscription->wasRecentlyCreated ? 201 : 200);
    }
}
