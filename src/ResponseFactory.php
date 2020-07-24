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

namespace Invinbg\HyperfResponse;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class ResponseFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);

        return make(Response::class, [
            null,
            $config,
        ]);
    }
}