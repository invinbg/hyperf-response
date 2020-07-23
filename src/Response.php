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

use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\Collection;
use Hyperf\Utils\Context;
use Hyperf\Utils\Contracts\Arrayable;
use Hyperf\HttpServer\Response as BaseResponse;
use Invinbg\Hyperf\Response\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class Response extends BaseResponse implements ResponseInterface
{
    /**
     * @var bool
     */
    private $withHttpStatus = false;

    public function __construct(?PsrResponseInterface $response = null, ConfigInterface $config)
    {
        parent::__construct($response);

        $this->withHttpStatus = $config->get('response.withHttpStatus', false);

    }

    public function __get($name)
    {
        if (in_array($name, ['data', 'code'])) {
            return $this->{$name}();
        }
    }

    public function __set($name, $value)
    {
        if (in_array($name, ['data', 'code'])) {
            return Context::set(__CLASS__ . ':' . $name, $value);
        }
    }

    public function withCode($code = 200): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * 设置Response的数据.
     * @param $data
     * @param mixed $override
     * @return $this
     */
    public function withData($data, $override = false): self
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }
        foreach ((array) $data as $key => $value) {
            if (is_numeric($key)) {
                if ($this->data->has($key) && $override) {
                    $this->data = $this->data->put($key, $value);
                } else {
                    $this->data = $this->data->push($value);
                }
            } else {
                $this->data = $this->data->put($key, $value);
            }
        }

        return $this;
    }

    /**
     *
     * 成功返回
     *
     * @param string $message
     *
     * @return PsrResponseInterface
     */
    public function success($message = '请求成功'): PsrResponseInterface
    {
        return $this->json([
            'code' => $this->code ?? 200,
            'data' => $this->data,
            'message' => $message,
        ])->withStatus(200);
    }

    /**
     *
     * 失败返回
     *
     * @param string $message
     *
     * @return PsrResponseInterface
     */
    public function error($message = '请求失败'): PsrResponseInterface
    {
        return $this->json([
            'code' => $this->code ?? 500,
            'data' => $this->data,
            'message' => $message,
        ])->withStatus($this->withHttpStatus ? ($this->code ?? 500) : 200);
    }

    /**
     * 获取data
     * @return mixed|null
     */
    private function data()
    {
        if (! Context::has(__CLASS__ . ':data')) {
            $this->data = new Collection();
        }
        return Context::get(__CLASS__ . ':data');
    }

    /**
     * 获取code
     * @return mixed|null
     */
    private function code()
    {
        return Context::get(__CLASS__ . ':code');
    }
}
