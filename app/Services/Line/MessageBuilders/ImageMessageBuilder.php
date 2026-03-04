<?php

namespace App\Services\Line\MessageBuilders;

use LINE\Clients\MessagingApi\Model\ImageMessage;

class ImageMessageBuilder
{
    public function build(string $originalUrl, string $previewUrl): ImageMessage
    {
        return new ImageMessage([
            'originalContentUrl' => $originalUrl,
            'previewImageUrl' => $previewUrl,
        ]);
    }
}
