<?php

namespace App\Services\Line\MessageBuilders;

use LINE\Clients\MessagingApi\Model\VideoMessage;

class VideoMessageBuilder
{
    public function build(string $originalUrl, string $previewUrl, string $trackingId = null): VideoMessage
    {
        $data = [
            'originalContentUrl' => $originalUrl,
            'previewImageUrl' => $previewUrl,
        ];

        if ($trackingId) {
            $data['trackingId'] = $trackingId;
        }

        return new VideoMessage($data);
    }
}
