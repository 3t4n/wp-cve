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
 * Class Stacktrace
 * @method null|string getFunction()
 * @method null|string getLineno()
 * @method null|string getFilename()
 * @method null|string getModule()
 * @method null|string getType()
 * @method self setFunction(string $function)
 * @method self setLineno(string $lineno)
 * @method self setModule(string $module)
 * @method self setType(string $type)
 * @method self setFilename(string $filename)
 * @package Payever\Sdk\Core\Apm\Events\Error
 */
class StacktraceEntity extends ApmRequestEntity
{
    /** @var string $function  */
    protected $function;

    /** @var string $lineno  */
    protected $lineno;

    /** @var string $filename  */
    protected $filename;

    /** @var string $abs_path  */
    protected $abs_path;

    /** @var string $module  */
    protected $module;

    /** @var string $type  */
    protected $type;

    /**
     * @return string
     */
    public function getAbsPath()
    {
        return $this->abs_path;
    }

    /**
     * @param $absPath
     * @return $this
     */
    public function setAbsPath($absPath)
    {
        $this->abs_path = $absPath;

        return $this;
    }
}
