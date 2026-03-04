<?php

namespace App\Services\Line\MessageBuilders;

use LINE\Clients\MessagingApi\Model\LocationMessage;

class LocationMessageBuilder
{
    public function build(string $title, string $address, float $latitude, float $longitude): LocationMessage
    {
        return new LocationMessage([
            'title' => $title,
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }
}
