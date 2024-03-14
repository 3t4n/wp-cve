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

namespace Payever\Sdk\Core\Apm\Events\Error;

use Payever\Sdk\Core\Http\ApmRequestEntity;

/**
 * Class ExceptionEntity
 * @method null|string getMessage()
 * @method null|string getType()
 * @method null|string getCode()
 * @method StacktraceEntity[] getStacktrace()
 * @method self setStacktrace(array $stacktrace)
 * @method self setMessage(string $message)
 * @method self setType(string $type)
 * @method self setCode(string $code)
 */
class ExceptionEntity extends ApmRequestEntity
{
    /** @var string $message */
    protected $message;

    /** @var string $type */
    protected $type;

    /** @var string $code */
    protected $code;

    /** @var StacktraceEntity[] $stacktrace */
    protected $stacktrace = [];

    /**
     * @param StacktraceEntity $stacktrace
     * @return $this
     */
    public function addStacktrace(StacktraceEntity $stacktrace)
    {
        $this->stacktrace[] = $stacktrace;

        return $this;
    }

    /**
     * @return StacktraceEntity
     */
    public function getStacktraceEntity()
    {
        return new StacktraceEntity();
    }
}
