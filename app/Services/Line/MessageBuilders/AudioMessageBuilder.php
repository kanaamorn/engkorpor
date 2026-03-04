<?php

namespace App\Services\Line\MessageBuilders;

use LINE\Clients\MessagingApi\Model\AudioMessage;

class AudioMessageBuilder
{
    public function build(string $originalUrl, int $duration): AudioMessage
    {
        return new AudioMessage([
            'originalContentUrl' => $originalUrl,
            'duration' => $duration,
        ]);
    }
}
