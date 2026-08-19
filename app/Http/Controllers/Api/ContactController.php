<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request): JsonResponse
    {
        $message = ContactMessage::create($request->validated() + ['status' => 'new']);

        return response()->json([
            'message' => 'Bedankt. We nemen zo snel mogelijk contact met je op.',
            'data' => ['id' => $message->getKey()],
        ], 201);
    }
}
