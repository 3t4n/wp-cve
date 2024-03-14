<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim-Psr7/blob/master/LICENSE.md (MIT License)
 */
declare (strict_types=1);
namespace Prokerala\Astrology\Vendor\Slim\Psr7\Factory;

use Prokerala\Astrology\Vendor\Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Prokerala\Astrology\Vendor\Slim\Psr7\Response;
class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createResponse(int $code = StatusCodeInterface::STATUS_OK, string $reasonPhrase = '') : ResponseInterface
    {
        $res = new Response($code);
        if ($reasonPhrase !== '') {
            $res = $res->withStatus($code, $reasonPhrase);
        }
        return $res;
    }
}
