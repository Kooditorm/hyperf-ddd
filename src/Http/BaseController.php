<?php
declare(strict_types=1);

namespace DCore\Http;

use DCore\Http\Component\Request;
use DCore\Http\Component\Response;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/**
 * Class BaseController
 * @package DCore\Http
 */
abstract class BaseController
{
    /** @var Request 请求处理 */
    #[Inject]
    protected Request $request;
    #[Inject]
    protected Response $response;


    /**
     * @param  array|object  $data
     * @return ResponseInterface
     */
    public function success(array|object $data = []): ResponseInterface
    {
        return $this->response->success($data);
    }

    /**
     * @param  string  $message
     * @param  int  $code
     * @return ResponseInterface
     */
    public function failed(string $message, int $code = 1000): ResponseInterface
    {
        return $this->response->failed($message, $code);
    }
}
