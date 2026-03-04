<?php

namespace App\Services\Line;

use LINE\Clients\MessagingApi\Configuration;
use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use GuzzleHttp\Client;

class LineClientService
{
    protected MessagingApiApi $client;

    public function __construct()
    {
        $channelToken = config('line-bot.channel_access_token');

        $config = new Configuration();
        $config->setAccessToken($channelToken);

        $this->client = new MessagingApiApi(new Client(), $config);
    }

    public function getClient(): MessagingApiApi
    {
        return $this->client;
    }
}
