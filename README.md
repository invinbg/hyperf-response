# hyperf-response
response for hyperf

在config/dependencies.php中添加

```Hyperf\HttpServer\Contract\ResponseInterface::class => Invinbg\Hyperf\Response\ResponseFactory::class```

或直接注入```Invinbg\Hyperf\Response\Contract\ResponseInterface```来使用
