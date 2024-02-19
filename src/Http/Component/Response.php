<?php
declare(strict_types=1);

namespace DCore\Http\Component;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Response as HttpServerResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * Class Response
 * @package DCore\Http\Component
 */
class Response extends HttpServerResponse
{
    protected int $statusCode = 200;

    /**
     * @param  array|object  $data
     * @return ResponseInterface
     */
    public function success(array|object $data): ResponseInterface
    {
        return $this->status($this->statusCode, 'success', $data);
    }

    /**
     * @param  string  $message
     * @param  int  $code
     * @return ResponseInterface
     */
    public function failed(string $message, int $code = 1000): ResponseInterface
    {
        return $this->status($code, $message);
    }

    /**
     * 调整返回数据格式
     *
     * @param  int  $code
     * @param  string  $message
     * @param  array|object  $data
     * @return ResponseInterface
     */
    public function status(int $code, string $message, array|object $data = []): ResponseInterface
    {
        return $this->respond(compact('code', 'message', 'data'));
    }

    /**
     * 返回数据
     *
     * @param  array|object  $data
     * @param  array  $headers
     * @return ResponseInterface|StreamInterface
     */
    public function respond(array|object $data, array $headers = []): StreamInterface|ResponseInterface
    {
        $content = $this->toJson($data);

        $response = $this->getResponse();

        if (!is_null($response)) {
            if (!empty($headers)) {
                foreach ($headers as $key => $value) {
                    $response = $response->withAddedHeader($key, $value);
                }
            }
            $response = $response->withAddedHeader('Content-Type', 'application/json; charset=utf-8');
            return $response->withBody(new SwooleStream($content));
        }

        throw new RuntimeException('response is null');
    }
}
