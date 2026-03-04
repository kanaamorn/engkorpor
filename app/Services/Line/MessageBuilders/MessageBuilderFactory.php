<?php

namespace App\Services\Line\MessageBuilders;

class MessageBuilderFactory
{
    public function __construct(
        protected TextMessageBuilder $textBuilder,
        protected ImageMessageBuilder $imageBuilder,
        protected VideoMessageBuilder $videoBuilder,
        protected AudioMessageBuilder $audioBuilder,
        protected LocationMessageBuilder $locationBuilder,
        protected StickerMessageBuilder $stickerBuilder,
        protected FlexMessageBuilder $flexBuilder,
        protected TemplateMessageBuilder $templateBuilder
    ) {}

    public function buildResponses(string $messageType, mixed $userMessage): array
    {
        $messages = [];

        switch ($messageType) {
            case 'text':
                // For text messages, respond with multiple message types as example
                $messages[] = $this->textBuilder->build("You said: " . $userMessage);
                $messages[] = $this->flexBuilder->buildWelcomeMessage($userMessage);
                // Optionally add more message types
                // $messages[] = $this->templateBuilder->buildButtonsTemplate($userMessage);
                break;

            case 'image':
                $messages[] = $this->textBuilder->build("Nice image! 📷");
                $messages[] = $this->imageBuilder->build(
                    'https://example.com/response-image.jpg',
                    'https://example.com/response-thumb.jpg'
                );
                break;

            case 'video':
                $messages[] = $this->textBuilder->build("Cool video! 🎬");
                $messages[] = $this->videoBuilder->build(
                    'https://example.com/sample-video.mp4',
                    'https://example.com/video-thumb.jpg'
                );
                break;

            case 'audio':
                $messages[] = $this->textBuilder->build("Nice audio! 🎵");
                $messages[] = $this->audioBuilder->build(
                    'https://example.com/sample-audio.m4a',
                    5000 // duration in milliseconds
                );
                break;

            case 'location':
                $messages[] = $this->textBuilder->build(
                    "You shared location: {$userMessage['title']} at {$userMessage['address']}"
                );
                $messages[] = $this->locationBuilder->build(
                    'Example Location',
                    '123 Example Street',
                    35.65910807942215,
                    139.70372892916203
                );
                break;

            case 'sticker':
                $messages[] = $this->textBuilder->build("Nice sticker! 😊");
                $messages[] = $this->stickerBuilder->build('446', '1988'); // Example sticker
                break;

            case 'file':
                $messages[] = $this->textBuilder->build("File received! 📁");
                break;

            default:
                $messages[] = $this->textBuilder->build(
                    "Sorry, I don't know how to handle this type of message yet."
                );
                break;
        }

        // Limit to 5 messages per reply (LINE's limit)
        return array_slice($messages, 0, 5);
    }
}
