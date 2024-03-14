<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\Exception;

use WPPayVendor\Symfony\Contracts\HttpClient\ResponseInterface;
/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ProviderException extends \WPPayVendor\Symfony\Component\Translation\Exception\RuntimeException implements \WPPayVendor\Symfony\Component\Translation\Exception\ProviderExceptionInterface
{
    private $response;
    private $debug;
    public function __construct(string $message, \WPPayVendor\Symfony\Contracts\HttpClient\ResponseInterface $response, int $code = 0, ?\Exception $previous = null)
    {
        $this->response = $response;
        $this->debug = $response->getInfo('debug') ?? '';
        parent::__construct($message, $code, $previous);
    }
    public function getResponse() : \WPPayVendor\Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
    public function getDebug() : string
    {
        return $this->debug;
    }
}
