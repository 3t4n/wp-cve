<?php

namespace Dotdigital_WordPress_Vendor\Http\Client\Promise;

use Dotdigital_WordPress_Vendor\Http\Client\Exception;
use Dotdigital_WordPress_Vendor\Http\Promise\Promise;
final class HttpRejectedPromise implements Promise
{
    /**
     * @var Exception
     */
    private $exception;
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }
    /**
     * {@inheritdoc}
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null)
    {
        if (null === $onRejected) {
            return $this;
        }
        try {
            $result = $onRejected($this->exception);
            if ($result instanceof Promise) {
                return $result;
            }
            return new HttpFulfilledPromise($result);
        } catch (Exception $e) {
            return new self($e);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        return Promise::REJECTED;
    }
    /**
     * {@inheritdoc}
     */
    public function wait($unwrap = \true)
    {
        if ($unwrap) {
            throw $this->exception;
        }
    }
}
