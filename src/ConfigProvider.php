<?php
declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Invinbg\Hyperf\Response;

use Invinbg\Hyperf\Response\Contract\ResponseInterface;

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