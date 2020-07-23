<?php

namespace Invinbg\Hyperf\Response;

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