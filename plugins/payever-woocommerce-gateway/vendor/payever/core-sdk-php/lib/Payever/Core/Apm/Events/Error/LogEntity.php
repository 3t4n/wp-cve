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

/**
 * Class LogEntity
 * @method null|string getLevel()
 * @method self setLevel(string $level)
 */
class LogEntity extends ExceptionEntity
{
    /** @var string $level */
    protected $level;
}
