<?php

namespace App\Services\Line\MessageBuilders;

use LINE\Clients\MessagingApi\Model\TemplateMessage;
use LINE\Clients\MessagingApi\Model\ButtonsTemplate;
use LINE\Clients\MessagingApi\Model\ConfirmTemplate;
use LINE\Clients\MessagingApi\Model\CarouselTemplate;
use LINE\Clients\MessagingApi\Model\CarouselColumn;
use LINE\Clients\MessagingApi\Model\MessageAction;
use LINE\Clients\MessagingApi\Model\URIAction;
use LINE\Clients\MessagingApi\Model\PostbackAction;

class TemplateMessageBuilder
{
    public function buildButtonsTemplate(string $text): TemplateMessage
    {
        $buttonsTemplate = new ButtonsTemplate([
            'thumbnailImageUrl' => 'https://example.com/button-image.jpg',
            'imageAspectRatio' => 'rectangle',
            'imageSize' => 'cover',
            'imageBackgroundColor' => '#FFFFFF',
            'title' => 'Menu',
            'text' => $text,
            'defaultAction' => new URIAction([
                'label' => 'View detail',
                'uri' => 'https://example.com'
            ]),
            'actions' => [
                new PostbackAction([
                    'label' => 'Buy',
                    'data' => 'action=buy&itemid=123'
                ]),
                new PostbackAction([
                    'label' => 'Add to cart',
                    'data' => 'action=add&itemid=123'
                ]),
                new URIAction([
                    'label' => 'View detail',
                    'uri' => 'https://example.com'
                ])
            ]
        ]);

        return new TemplateMessage([
            'altText' => 'Buttons template',
            'template' => $buttonsTemplate
        ]);
    }

    public function buildConfirmTemplate(string $text): TemplateMessage
    {
        $confirmTemplate = new ConfirmTemplate([
            'text' => $text,
            'actions' => [
                new MessageAction([
                    'label' => 'Yes',
                    'text' => 'yes'
                ]),
                new MessageAction([
                    'label' => 'No',
                    'text' => 'no'
                ])
            ]
        ]);

        return new TemplateMessage([
            'altText' => 'Confirm template',
            'template' => $confirmTemplate
        ]);
    }

    public function buildCarouselTemplate(array $items): TemplateMessage
    {
        $columns = array_map(function ($item) {
            return new CarouselColumn([
                'thumbnailImageUrl' => $item['image'] ?? 'https://example.com/default.jpg',
                'imageBackgroundColor' => '#FFFFFF',
                'title' => $item['title'] ?? 'Title',
                'text' => $item['text'] ?? 'Description',
                'defaultAction' => new URIAction([
                    'label' => 'View',
                    'uri' => $item['url'] ?? 'https://example.com'
                ]),
                'actions' => [
                    new PostbackAction([
                        'label' => 'Select',
                        'data' => 'action=select&id=' . ($item['id'] ?? '0')
                    ]),
                    new URIAction([
                        'label' => 'View',
                        'uri' => $item['url'] ?? 'https://example.com'
                    ])
                ]
            ]);
        }, $items);

        $carouselTemplate = new CarouselTemplate([
            'columns' => array_slice($columns, 0, 10) // Maximum 10 columns
        ]);

        return new TemplateMessage([
            'altText' => 'Carousel template',
            'template' => $carouselTemplate
        ]);
    }
}
