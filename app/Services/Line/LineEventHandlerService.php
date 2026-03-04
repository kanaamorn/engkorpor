<?php

namespace App\Services\Line;

use Illuminate\Support\Facades\Log;
use LINE\Clients\MessagingApi\Model\ReplyMessageRequest;
use App\Services\Line\MessageBuilders\MessageBuilderFactory;

class LineEventHandlerService
{
    public function __construct(
        protected LineClientService $clientService,
        protected MessageBuilderFactory $messageFactory
    ) {}

    public function handle($event): void
    {
        // Only handle message events with reply tokens
        if (!method_exists($event, 'getReplyToken')) {
            return;
        }

        $replyToken = $event->getReplyToken();

        if (empty($replyToken)) {
            return;
        }

        try {
            $messages = $this->buildResponseMessages($event);

            if (!empty($messages)) {
                $this->sendReply($replyToken, $messages);
            }
        } catch (\Exception $e) {
            Log::error('Failed to handle event', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    protected function buildResponseMessages($event): array
    {
        $messageType = $this->detectMessageType($event);
        $userMessage = $this->extractUserMessage($event);

        // Build different response types based on the incoming message
        return $this->messageFactory->buildResponses($messageType, $userMessage);
    }

    protected function detectMessageType($event): string
    {
        if (!method_exists($event, 'getMessage')) {
            return 'unknown';
        }

        $message = $event->getMessage();

        return match (true) {
            method_exists($message, 'getText') => 'text',
            method_exists($message, 'getImage') => 'image',
            method_exists($message, 'getVideo') => 'video',
            method_exists($message, 'getAudio') => 'audio',
            method_exists($message, 'getFile') => 'file',
            method_exists($message, 'getLocation') => 'location',
            method_exists($message, 'getSticker') => 'sticker',
            default => 'unknown'
        };
    }

    protected function extractUserMessage($event): mixed
    {
        if (!method_exists($event, 'getMessage')) {
            return null;
        }

        $message = $event->getMessage();

        return match (true) {
            method_exists($message, 'getText') => $message->getText(),
            method_exists($message, 'getLocation') => [
                'title' => $message->getTitle(),
                'address' => $message->getAddress(),
                'latitude' => $message->getLatitude(),
                'longitude' => $message->getLongitude(),
            ],
            method_exists($message, 'getSticker') => [
                'packageId' => $message->getPackageId(),
                'stickerId' => $message->getStickerId(),
            ],
            default => null
        };
    }

    protected function sendReply(string $replyToken, array $messages): void
    {
        $reply = new ReplyMessageRequest([
            'replyToken' => $replyToken,
            'messages' => $messages,
        ]);

        $this->clientService->getClient()->replyMessage($reply);
    }
}
