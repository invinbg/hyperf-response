<?php

namespace Invinbg\Hyperf\Response;
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                ResponseInterface::class => ResponseFactory::class,
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for response component.',
                    'source' => __DIR__ . '/../publish/response.php',
                    'destination' => BASE_PATH . '/config/autoload/response.php',
                ],
            ],
            'listeners' => [
            ],
            'processes' => [
            ],
        ];
    }
}