<?php

namespace Payever\Sdk\Core\Http\RequestEntity;

use Payever\Sdk\Core\Apm\Events\Error as ErrorEvent;
use Payever\Sdk\Core\Apm\Events\Metadata;
use Payever\Sdk\Core\Apm\Events\Transaction;
use Payever\Sdk\Core\Http\RequestEntity;

/**
 * Class ApmEventsRequest
 */
class ApmEventsRequest extends RequestEntity
{
    /** @var null|Metadata $metadata */
    protected $metadata;

    /** @var null|ErrorEvent $error */
    protected $error;

    /** @var null|Transaction $transaction */
    protected $transaction;

    /**
     * @param Metadata $metadata
     * @return $this
     */
    public function setMetaEvent(Metadata $metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @param ErrorEvent $error
     * @return $this
     */
    public function setErrorEvent(ErrorEvent $error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @param Transaction $transaction
     * @return $this
     */
    public function setTransactionEvent(Transaction $transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * @return null|Metadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return null|ErrorEvent
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return null|Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
