<?php

namespace App\Http\Controllers;

use App\Services\WebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handle(Request $request)
    {
        try {
            // Validate basic JSON payload
            $payload = $request->validate([
                '*' => 'required'
            ]);

            // Determine webhook source
            $source = $this->determineWebhookSource($request);
            
            // Process webhook based on source
            $result = $this->webhookService->processWebhook($source, $payload, $request->headers->all());
            
            return response()->json([
                'message' => 'Webhook processed successfully',
                'data' => $result
            ], 200);

        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Webhook processing failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    private function determineWebhookSource(Request $request)
    {
        // Detect source based on headers or payload characteristics
        if ($request->hasHeader('X-GitHub-Event')) {
            return 'github';
        }

        if ($request->hasHeader('Stripe-Signature')) {
            return 'stripe';
        }

        return null;
    }
}    
