<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request): JsonResponse
    {
        $lead = Lead::create($request->validated() + [
            'consented_at' => now(),
            'status' => 'new',
            'source_url' => $request->headers->get('referer'),
        ]);

        return response()->json([
            'message' => 'Bedankt. Je aanvraag is ontvangen. De geselecteerde partner neemt persoonlijk contact met je op.',
            'data' => ['id' => $lead->getKey(), 'status' => $lead->status],
        ], 201);
    }
}
