<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Http\Client\Common\Plugin;

use Dotdigital_WordPress_Vendor\Http\Client\Common\Plugin;
use Dotdigital_WordPress_Vendor\Http\Promise\Promise;
use Dotdigital_WordPress_Vendor\Psr\Http\Client\ClientExceptionInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ResponseInterface;
/**
 * Record HTTP calls.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class HistoryPlugin implements Plugin
{
    /**
     * Journal use to store request / responses / exception.
     *
     * @var Journal
     */
    private $journal;
    public function __construct(Journal $journal)
    {
        $this->journal = $journal;
    }
    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first) : Promise
    {
        $journal = $this->journal;
        return $next($request)->then(function (ResponseInterface $response) use($request, $journal) {
            $journal->addSuccess($request, $response);
            return $response;
        }, function (ClientExceptionInterface $exception) use($request, $journal) {
            $journal->addFailure($request, $exception);
            throw $exception;
        });
    }
}
