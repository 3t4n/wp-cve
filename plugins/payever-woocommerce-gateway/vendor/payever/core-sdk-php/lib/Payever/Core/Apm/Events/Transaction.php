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

use Payever\Sdk\Core\Apm\Events\Transaction\SpanCountEntity;
use Payever\Sdk\Core\Http\ApmRequestEntity;

/**
 * Class Transaction
 * @method null|string getId()
 * @method null|int getTimestamp()
 * @method null|string getName()
 * @method int getDuration()
 * @method string getType()
 * @method self setId(string $id)
 * @method self setTimestamp(string $timestamp)
 * @method self setName(string $name)
 * @method self setDuration(int $duration)
 * @method self setType(string $type)
 */
class Transaction extends ApmRequestEntity
{
    /** @var null|string $id */
    protected $id;

    /** @var null|int $timestamp */
    protected $timestamp;

    /** @var null|string $name */
    protected $name;

    /** @var int $duration */
    protected $duration = 0;

    /** @var string $type */
    protected $type = 'background_job';

    /** @var string $trace_id */
    protected $trace_id;

    /** @var SpanCountEntity $span_count */
    protected $span_count;

    /** @var ContextEntity $context */
    protected $context;

    /**
     * @param $data
     */
    public function __construct($data = [])
    {
        if (!isset($data['context'])) {
            $data['context'] = new ContextEntity();
        }

        if (!isset($data['trace_id'])) {
            $data['trace_id'] = uniqid(microtime(true));
        }

        if (!isset($data['span_count'])) {
            $data['span_count'] = new SpanCountEntity();
        }

        parent::__construct($data);
    }

    /**
     * @param SpanCountEntity|array|string $spanCount
     * @return $this
     */
    public function setSpanCount($spanCount)
    {
        $this->span_count = $this->getClassInstance(SpanCountEntity::class, $spanCount);

        return $this;
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
     * @return SpanCountEntity
     */
    public function getSpanCount()
    {
        return $this->span_count;
    }

    /**
     * @return string
     */
    public function getTraceId()
    {
        return $this->trace_id;
    }

    /**
     * @param string $traceId
     * @return $this
     */
    public function setTraceId($traceId)
    {
        $this->trace_id = $traceId;

        return $this;
    }
}
