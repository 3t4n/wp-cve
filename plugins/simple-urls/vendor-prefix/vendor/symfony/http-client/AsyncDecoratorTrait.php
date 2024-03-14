<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LassoLiteVendor\Symfony\Component\HttpClient;

use LassoLiteVendor\Symfony\Component\HttpClient\Response\AsyncResponse;
use LassoLiteVendor\Symfony\Component\HttpClient\Response\ResponseStream;
use LassoLiteVendor\Symfony\Contracts\HttpClient\ResponseInterface;
use LassoLiteVendor\Symfony\Contracts\HttpClient\ResponseStreamInterface;
/**
 * Eases with processing responses while streaming them.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
trait AsyncDecoratorTrait
{
    use DecoratorTrait;
    /**
     * {@inheritdoc}
     *
     * @return AsyncResponse
     */
    public abstract function request(string $method, string $url, array $options = []) : ResponseInterface;
    /**
     * {@inheritdoc}
     */
    public function stream($responses, float $timeout = null) : ResponseStreamInterface
    {
        if ($responses instanceof AsyncResponse) {
            $responses = [$responses];
        } elseif (!\is_iterable($responses)) {
            throw new \TypeError(\sprintf('"%s()" expects parameter 1 to be an iterable of AsyncResponse objects, "%s" given.', __METHOD__, \get_debug_type($responses)));
        }
        return new ResponseStream(AsyncResponse::stream($responses, $timeout, static::class));
    }
}
