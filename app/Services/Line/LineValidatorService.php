<?php

namespace App\Services\Line;

class LineValidatorService
{
    protected string $channelSecret;

    public function __construct()
    {
        $this->channelSecret = config('line-bot.channel_secret');
    }

    public function validateSignature(string $body, ?string $signature): bool
    {
        $expectedSignature = base64_encode(
            hash_hmac('sha256', $body, $this->channelSecret, true)
        );

        return hash_equals($expectedSignature, $signature ?? '');
    }
}
