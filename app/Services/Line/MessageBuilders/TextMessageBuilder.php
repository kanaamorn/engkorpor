<?php

namespace App\Services\Line\MessageBuilders;

use LINE\Clients\MessagingApi\Model\TextMessage;

class TextMessageBuilder
{
    public function build(string $text, array $emojis = []): TextMessage
    {
        $data = ['text' => $text];

        if (!empty($emojis)) {
            $data['emojis'] = $emojis;
        }

        return new TextMessage($data);
    }

    public function buildWithQuickReply(string $text, array $items): TextMessage
    {
        return new TextMessage([
            'text' => $text,
            'quickReply' => [
                'items' => $items
            ]
        ]);
    }
}
