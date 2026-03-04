<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Line\LineClientService;
use App\Services\Line\LineValidatorService;
use App\Services\Line\LineEventHandlerService;
use LINE\Parser\EventRequestParser;

class LineWebhookController extends Controller
{
    public function __construct(
        protected LineClientService $clientService,
        protected LineValidatorService $validatorService,
        protected LineEventHandlerService $eventHandler
    ) {}

    public function handle(Request $request)
    {
        $signature = $request->header('X-Line-Signature');
        $body = $request->getContent();

        // Validate signature
        if (!$this->validatorService->validateSignature($body, $signature)) {
            Log::warning('Invalid signature', ['signature' => $signature]);
            abort(403, 'Invalid signature');
        }

        // Parse events
        $events = EventRequestParser::parseEventRequest(
            $body,
            config('line-bot.channel_secret'),
            $signature
        );

        // Handle each event
        foreach ($events->getEvents() as $event) {
            $this->eventHandler->handle($event);
        }

        return response()->json(['status' => 'ok']);
    }
}
