<?php

namespace App\Services;

use App\Repositories\WebhookLogRepository;
use App\Repositories\GithubCommitRepository;
use App\Repositories\StripeTransactionRepository;

class WebhookService
{
    private $webhookLogRepository;
    private $githubCommitRepository;
    private $stripeTransactionRepository;

    public function __construct(
        WebhookLogRepository $webhookLogRepository,
        GithubCommitRepository $githubCommitRepository,
        StripeTransactionRepository $stripeTransactionRepository
    ) {
        $this->webhookLogRepository = $webhookLogRepository;
        $this->githubCommitRepository = $githubCommitRepository;
        $this->stripeTransactionRepository = $stripeTransactionRepository;
    }

    public function processWebhook(string $source, array $payload, array $headers)
    {
        // Process based on source
        switch ($source) {
            case 'github':
                return $this->processGithubWebhook($payload);
            case 'stripe':
                return $this->processStripeWebhook($payload);
            default:
                return null;
        }
    }

    private function processGithubWebhook(array $payload)
    {
        // Extract commit information
        $commits = $payload['commits'] ?? [];
        $processedCommits = [];

        foreach ($commits as $commit) {
            $processedCommit = $this->githubCommitRepository->create([
                'commit_id' => $commit['id'],
                'message' => $commit['message'],
                'author_name' => $commit['author']['name'],
                'author_email' => $commit['author']['email'],
                'committed_at' => now()
            ]);
            $processedCommits[] = $processedCommit;
        }

        if(!empty($processedCommits)){
            $this->processWebhookLog('github', $payload);
        }

        return $processedCommits;
    }

    private function processStripeWebhook(array $payload)
    {
        // Process Stripe payment event
        if ($payload['type'] === 'payment_intent.succeeded') {
            $transaction = $this->stripeTransactionRepository->create([
                'transaction_id' => $payload['data']['object']['id'],
                'amount' => $payload['data']['object']['amount'] / 100, // Convert cents to dollars
                'currency' => $payload['data']['object']['currency'],
                'status' => 'completed',
                'metadata' => json_encode($payload['data']['object'])
            ]);

            if($transaction){
                $this->processWebhookLog('stripe', $payload);
            }

            return $transaction;
        }

        return null;
    }

    private function processWebhookLog($source, $payload){
        // Log the incoming webhook
        $this->webhookLogRepository->create([
            'source' => $source,
            'payload' => json_encode($payload),
            'status' => 'success'
        ]);
    }
}