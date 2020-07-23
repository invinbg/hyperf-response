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
namespace Invinbg\Hyperf\Response\Contract;

use Hyperf\HttpServer\Contract\ResponseInterface as BaseResponseInterface;
use Invinbg\Hyperf\Response\Response;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Class Response.
 */
interface ResponseInterface extends PsrResponseInterface, BaseResponseInterface
{
    /**
     *
     * 设置返回内容code值
     *
     * @param int $code
     *
     * @return Response
     */
    public function withCode($code = 200): Response;

    /**
     *
     * 设置返回内容data值
     *
     * @param      $data
     * @param bool $override
     *
     * @return Response
     */
    public function withData($data, $override = false): Response;

    /**
     *
     * 成功返回
     *
     * @param string $message
     *
     * @return PsrResponseInterface
     */
    public function success($message = '请求成功'): PsrResponseInterface;

    /**
     *
     * 失败返回
     *
     * @param string $message
     *
     * @return PsrResponseInterface
     */
    public function error($message = '请求失败'): PsrResponseInterface;
}
