<?php

namespace Dotdigital_WordPress_Vendor\Http\Promise;

/**
 * A rejected promise.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 *
 * @template-covariant T
 *
 * @implements Promise<T>
 */
final class RejectedPromise implements Promise
{
    /**
     * @var \Exception
     */
    private $exception;
    public function __construct(\Exception $exception)
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
            return new FulfilledPromise($onRejected($this->exception));
        } catch (\Exception $e) {
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
