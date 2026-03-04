<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\Clients\MessagingApi\Configuration;
use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use LINE\Clients\MessagingApi\Model\ReplyMessageRequest;
use LINE\Clients\MessagingApi\Model\TextMessage;
use LINE\Clients\MessagingApi\Model\FlexMessage;
use LINE\Parser\EventRequestParser;
use GuzzleHttp\Client;

class LineWebhookController extends Controller
{
    protected MessagingApiApi $client;
    protected string $channelSecret;

    public function __construct()
    {
        $this->channelSecret = config('line-bot.channel_secret');
        $channelToken = config('line-bot.channel_access_token');

        $config = new Configuration();
        $config->setAccessToken($channelToken);

        $this->client = new MessagingApiApi(new Client(), $config);
    }

    public function handle(Request $request)
    {
        $signature = $request->header('X-Line-Signature');
        $body = $request->getContent();

        if (!$this->isValidSignature($body, $signature)) {
            Log::warning('Invalid signature', ['signature' => $signature, 'body' => $body]);
            abort(403, 'Invalid signature');
        }

        $events = EventRequestParser::parseEventRequest($body, $this->channelSecret, $signature);

        foreach ($events->getEvents() as $event) {
            if ($this->isTextMessageEvent($event)) {
                $this->handleTextMessage($event);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    protected function isValidSignature(string $body, ?string $signature): bool
    {
        $expectedSignature = base64_encode(
            hash_hmac('sha256', $body, $this->channelSecret, true)
        );

        return hash_equals($expectedSignature, $signature ?? '');
    }

    protected function isTextMessageEvent($event): bool
    {
        return method_exists($event, 'getMessage') &&
            method_exists($event->getMessage(), 'getText');
    }

    protected function handleTextMessage($event): void
    {
        $replyToken = $event->getReplyToken();
        $message = $event->getMessage();

        // Only handle text messages
        if (method_exists($message, 'getText')) {
            $userMessage = $message->getText();

            $reply = new ReplyMessageRequest([
                'replyToken' => $replyToken,
                'messages' => [
                    $this->buildTextMessage($userMessage),
                    $this->buildFlexMessage($userMessage),
                ],
            ]);

            try {
                $this->client->replyMessage($reply);
            } catch (\Exception $e) {
                Log::error('Failed to send reply', ['error' => $e->getMessage()]);
            }
        } else {
            // For non-text messages, you can reply differently or ignore
            $reply = new ReplyMessageRequest([
                'replyToken' => $replyToken,
                'messages' => [
                    new TextMessage([
                        'text' => 'Sorry, I can only process text messages right now.'
                    ]),
                ],
            ]);
            $this->client->replyMessage($reply);
        }
    }


    protected function buildTextMessage(string $userMessage): TextMessage
    {
        return new TextMessage([
            'text' => 'You said: ' . $userMessage,
        ]);
    }

    protected function buildFlexMessage(string $userMessage): FlexMessage
    {
        $flexData = [
            "type" => "bubble",
            "hero" => [
                "type" => "image",
                "url" => "https://example.com/image.jpg",
                "size" => "full",
                "aspectRatio" => "20:13",
                "aspectMode" => "cover",
            ],
            "body" => [
                "type" => "box",
                "layout" => "vertical",
                "contents" => [
                    [
                        "type" => "text",
                        "text" => "Hello! You said: " . $userMessage,
                        "weight" => "bold",
                        "size" => "xl",
                    ],
                ],
            ],
        ];

        return new FlexMessage([
            'altText' => 'You have a new message',
            'contents' => $flexData, // just pass the array
        ]);
    }
}
