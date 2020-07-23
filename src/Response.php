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
use Hyperf\Utils\Arr;
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
    private $withHttpStatus;
    /**
     * @var mixed|string
     */
    private $codeKey = 'code';
    /**
     * @var mixed|string
     */
    private $dataKey = 'data';
    /**
     * @var mixed|string
     */
    private $messageKey = 'message';

    public function __construct(?PsrResponseInterface $response = null, ConfigInterface $config)
    {
        parent::__construct($response);

        $this->withHttpStatus = $config->get('response.withHttpStatus', false);

        $dataMapping = $config->get('response.data', []);

        $dataMapping = Arr::only($dataMapping, ['code', 'data', 'message']);

        if (count($dataMapping) !== count(array_unique($dataMapping))) {
            throw new \InvalidArgumentException('data mapping is invalid');
        }

        isset($dataMapping['code']) && $this->codeKey = $dataMapping['code'];

        isset($dataMapping['data']) && $this->dataKey = $dataMapping['data'];

        isset($dataMapping['message']) && $this->messageKey = $dataMapping['message'];
    }

    public function __get($name)
    {
        if (in_array($name, ['data', 'code', 'extra'])) {
            return $this->{$name}();
        }
    }

    public function __set($name, $value)
    {
        if (in_array($name, ['data', 'code', 'extra'])) {
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
     * 设置Response的扩展数据.
     * @param $data
     * @param mixed $override
     * @return $this
     */
    public function withExtraData($data, $override = false): self
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }
        foreach ((array) $data as $key => $value) {
            if (is_numeric($key)) {
                if ($this->extra->has($key) && $override) {
                    $this->extra = $this->extra->put($key, $value);
                } else {
                    $this->extra = $this->extra->push($value);
                }
            } else {
                $this->extra = $this->extra->put($key, $value);
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
        $data = [
            $this->codeKey => $this->code ?? 200,
            $this->dataKey => $this->data,
            $this->messageKey => $message,
        ];
        if($this->extra instanceof Arrayable) {
            $data = array_merge($data, $this->extra->toArray());
        }
        return $this->json($data)->withStatus(200);
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
        $data = [
            $this->codeKey => $this->code ?? 500,
            $this->dataKey => $this->data,
            $this->messageKey => $message,
        ];
        if($this->extra instanceof Arrayable) {
            $data = array_merge($data, $this->extra->toArray());
        }
        return $this->json($data)->withStatus($this->withHttpStatus ? ($this->code ?? 500) : 200);
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
     * 获取extra
     * @return mixed|null
     */
    private function extra()
    {
        if (! Context::has(__CLASS__ . ':extra')) {
            $this->extra = new Collection();
        }
        return Context::get(__CLASS__ . ':extra');
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
