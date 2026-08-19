<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PitoContentService;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    public function __construct(private readonly PitoContentService $content) {}

    public function __invoke(): JsonResponse
    {
        return response()->json($this->content->all());
    }

    public function municipalities(): JsonResponse
    {
        return response()->json(['data' => $this->content->municipalities()]);
    }
}
