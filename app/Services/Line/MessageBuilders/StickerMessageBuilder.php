<?php

namespace App\Services\Line\MessageBuilders;

use LINE\Clients\MessagingApi\Model\StickerMessage;

class StickerMessageBuilder
{
    public function build(string $packageId, string $stickerId): StickerMessage
    {
        return new StickerMessage([
            'packageId' => $packageId,
            'stickerId' => $stickerId,
        ]);
    }
}
