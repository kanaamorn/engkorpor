<?php

/**
 * Copyright 2020 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

return [
    'channel_access_token' => env('LINE_BOT_CHANNEL_ACCESS_TOKEN'),
    'channel_id' => env('LINE_BOT_CHANNEL_ID'),
    'channel_secret' => env('LINE_BOT_CHANNEL_SECRET'),
    'client' => [
        'config' => [],
    ],
    // Default media URLs for responses
    'default_media' => [
        'image_url' => env('LINE_DEFAULT_IMAGE_URL', 'https://example.com/default.jpg'),
        'video_url' => env('LINE_DEFAULT_VIDEO_URL', 'https://example.com/default.mp4'),
        'audio_url' => env('LINE_DEFAULT_AUDIO_URL', 'https://example.com/default.m4a'),
    ],

    // Sticker IDs for responses
    'stickers' => [
        'hello' => ['package_id' => '446', 'sticker_id' => '1988'],
        'thanks' => ['package_id' => '446', 'sticker_id' => '1989'],
        'ok' => ['package_id' => '446', 'sticker_id' => '2012'],
    ]
];
