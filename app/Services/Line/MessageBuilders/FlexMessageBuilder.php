<?php

namespace App\Services\Line\MessageBuilders;

use LINE\Clients\MessagingApi\Model\FlexMessage;

class FlexMessageBuilder
{
    public function buildWelcomeMessage(string $userMessage): FlexMessage
    {
        $flexData = [
            "type" => "bubble",
            "hero" => [
                "type" => "image",
                "url" => "https://example.com/hero-image.jpg",
                "size" => "full",
                "aspectRatio" => "20:13",
                "aspectMode" => "cover",
                "action" => [
                    "type" => "uri",
                    "uri" => "https://example.com"
                ]
            ],
            "body" => [
                "type" => "box",
                "layout" => "vertical",
                "contents" => [
                    [
                        "type" => "text",
                        "text" => "Welcome!",
                        "weight" => "bold",
                        "size" => "xl",
                        "margin" => "md"
                    ],
                    [
                        "type" => "text",
                        "text" => "You said: " . $userMessage,
                        "size" => "sm",
                        "color" => "#999999",
                        "margin" => "md",
                        "wrap" => true
                    ]
                ]
            ],
            "footer" => [
                "type" => "box",
                "layout" => "vertical",
                "spacing" => "sm",
                "contents" => [
                    [
                        "type" => "button",
                        "style" => "primary",
                        "height" => "sm",
                        "action" => [
                            "type" => "uri",
                            "label" => "Visit Website",
                            "uri" => "https://example.com"
                        ]
                    ],
                    [
                        "type" => "button",
                        "style" => "link",
                        "height" => "sm",
                        "action" => [
                            "type" => "message",
                            "label" => "Say Hello",
                            "text" => "Hello!"
                        ]
                    ]
                ]
            ]
        ];

        return new FlexMessage([
            'altText' => 'Welcome message',
            'contents' => $flexData,
        ]);
    }

    public function buildCarousel(array $items): FlexMessage
    {
        $bubbles = array_map(function ($item) {
            return $this->buildCarouselBubble($item);
        }, $items);

        $flexData = [
            "type" => "carousel",
            "contents" => $bubbles
        ];

        return new FlexMessage([
            'altText' => 'Carousel message',
            'contents' => $flexData,
        ]);
    }

    protected function buildCarouselBubble(array $item): array
    {
        return [
            "type" => "bubble",
            "hero" => [
                "type" => "image",
                "size" => "full",
                "aspectRatio" => "20:13",
                "aspectMode" => "cover",
                "url" => $item['image'] ?? 'https://example.com/default.jpg'
            ],
            "body" => [
                "type" => "box",
                "layout" => "vertical",
                "spacing" => "sm",
                "contents" => [
                    [
                        "type" => "text",
                        "text" => $item['title'] ?? 'Title',
                        "wrap" => true,
                        "weight" => "bold",
                        "size" => "xl"
                    ],
                    [
                        "type" => "text",
                        "text" => $item['description'] ?? 'Description',
                        "wrap" => true,
                        "size" => "sm"
                    ]
                ]
            ],
            "footer" => [
                "type" => "box",
                "layout" => "vertical",
                "contents" => [
                    [
                        "type" => "button",
                        "action" => [
                            "type" => "uri",
                            "label" => $item['buttonText'] ?? 'View',
                            "uri" => $item['buttonUrl'] ?? 'https://example.com'
                        ]
                    ]
                ]
            ]
        ];
    }
}
