# 安装
```composer require hyperf/hyperf-response```

# 发布配置文件
```php bin/hyperf.php vendor:publish invinbg/hyperf-response```

### 配置
```php
// 如果需要同步HttpStatus这里设置为true
'withHttpStatus' => false, // sync httpStatus
// 自定义response data 中的键名映射
'data' => [                // data mapping
    'code' => 'code',
    'data' => 'data',
    'message' => 'message'
]
```

# 使用
- 用hyperf-response来实现原有的`Hyperf\HttpServer\Contract\ResponseInterface`
```php
// config/autoload/dependencies.php
return [
    Hyperf\HttpServer\Contract\ResponseInterface::class => Invinbg\HyperfResponse\ResponseFactory::class,
];
```

- 通过依赖注入使用
```php
namespace App\Controller;

use Invinbg\HyperfResponse\Contract\ResponseInterface;

class DemoController
{
    public function response(ResponseInterface $response)
    {
        $user = User::query()->first();
        $data = [
            // ...额外的数据
        ];
        $extra = [
            // ...扩展数据
        ];
        // withData可以链式追加数据
        return $response->withData($user)->withData($data)->withExtraData($extra)->success();
    }
}
```

- 通过`@Inject`注解使用
```php
namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use Invinbg\HyperfResponse\Contract\ResponseInterface;

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
