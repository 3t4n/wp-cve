<?php

/**
 * PHP version 5.4 and 8.1
 *
 * @category  Apm Agent
 * @package   Payever\Core
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Core\Apm\Events;

use Payever\Sdk\Core\Http\ApmRequestEntity;
use Payever\Sdk\Core\Apm\Events\Error\ExceptionEntity;
use Payever\Sdk\Core\Apm\Events\Error\LogEntity;
use Psr\Log\LogLevel;

/**
 * Class Error
 * @method string getId()
 * @method string getTimestamp()
 * @method string getCulprit()
 * @method ContextEntity getContext()
 * @method LogEntity getLog()
 * @method ExceptionEntity getException()
 * @method self setId(string $id)
 * @method self setTimestamp(string $id)
 * @method self setCulprit(string $id)
 */
class Error extends ApmRequestEntity
{
    /** @var string $id */
    protected $id;

    /** @var string $timestamp */
    protected $timestamp;

    /** @var string $culprit */
    protected $culprit;

    /** @var ContextEntity $context */
    protected $context;

    /** @var LogEntity $log */
    protected $log;

    /** @var ExceptionEntity $exception */
    protected $exception;

    /** @var string $transaction_id */
    protected $transaction_id;

    /** @var string $parent_id */
    protected $parent_id;

    /** @var string $trace_id */
    protected $trace_id;

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (!isset($data['log'])) {
            $data['log'] = new LogEntity();
        }

        if (!isset($data['exception'])) {
            $data['exception'] = new ExceptionEntity();
        }

        if (!isset($data['context'])) {
            $data['context'] = new ContextEntity();
        }

        parent::__construct($data);
    }

    /**
     * @param ContextEntity|array|string $context
     * @return $this
     */
    public function setContext($context)
    {
        $this->context = $this->getClassInstance(ContextEntity::class, $context);

        return $this;
    }

    /**
     * @param LogEntity|array|string $log
     * @return $this
     */
    public function setLog($log)
    {
        $this->log = $this->getClassInstance(LogEntity::class, $log);
        $this->exception = null;

        return $this;
    }

    /**
     * @param ExceptionEntity|array|string $exception
     * @return $this
     */
    public function setException($exception)
    {
        $this->exception = $this->getClassInstance(ExceptionEntity::class, $exception);
        $this->log = null;

        return $this;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setTransactionId($id)
    {
        $this->transaction_id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setParentId($id)
    {
        $this->parent_id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setTraceId($id)
    {
        $this->trace_id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTraceId()
    {
        return $this->trace_id;
    }
}
