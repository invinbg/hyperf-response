# 安装

```composer require hyperf/hyperf-response```

# 发布配置文件
```php bin/hyperf.php vendor:publish invinbg/hyperf-response```

### 配置
```php
// 如果需要同步HttpStatus这里设置为true
'withHttpStatus' => false, // sync httpStatus
```

# 使用

- 用hyperf-response来实现原有的`Hyperf\HttpServer\Contract\ResponseInterface`
```php
// config/autoload/dependencies.php
return [
    Hyperf\HttpServer\Contract\ResponseInterface::class => Invinbg\Hyperf\Response\ResponseFactory::class,
];
```

- 通过依赖注入使用

```php
namespace App\Controller;

use Invinbg\Hyperf\Response\Contract\ResponseInterface;

class DemoController
{
    public function response(ResponseInterface $response)
    {
        $user = User::query()->first();
        return $response->withData($user)->success();
    }
}
```

- 通过`@Inject`注解使用

```php
namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use Invinbg\Hyperf\Response\Contract\ResponseInterface;

class DemoController
{
    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    public function response()
    {
        return $this->response->withCode(401)->error('unauthorized.');
    }
}
```
