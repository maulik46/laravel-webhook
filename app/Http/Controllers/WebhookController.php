<?php

namespace App\Http\Controllers;

use App\Models\GithubCommit;
use App\Models\StripeTransaction;
use App\Services\WebhookService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class WebhookController extends Controller
{
    use ApiResponseTrait;

    private $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handle(Request $request)
    {
        if (!$request->isJson()) {
            return $this->errorResponse('Invalid request. Must be JSON.', Response::HTTP_BAD_REQUEST);
        }

        try {
            $source = $this->determineWebhookSource($request);

            $payload = $this->validatePayload($request, $source);

            $result = $this->webhookService->processWebhook($source, $payload, $request->headers->all());

            return $this->successResponse(
                'Webhook processed successfully',
                [
                    'webhook_status' => $result
                ], 
                Response::HTTP_OK
            );
        }  
        catch (ValidationException $e) {
            return $this->errorResponse('Validation failed: ' . $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } 
        catch (Throwable $e) {
            return $this->errorResponse('Webhook processing failed: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function validatePayload(Request $request, ?string $source): array
    {
        $validationRules = match ($source) {
            'github' => GithubCommit::githubRules(),
            'stripe' => StripeTransaction::stripeRules(),
            default  => ['*' => 'required'],
        };

        return $request->validate($validationRules);
    }

    private function determineWebhookSource(Request $request): ?string
    {
        return match (true) {
            $request->hasHeader('X-GitHub-Event') => 'github',
            $request->hasHeader('Stripe-Signature') => 'stripe',
            default => null,
        };
    }
}    
