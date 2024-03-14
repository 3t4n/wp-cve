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

namespace Payever\Sdk\Core\Apm\Events\Transaction;

use Payever\Sdk\Core\Http\ApmRequestEntity;

/**
 * Class SpanCountEntity
 * @method integer getStarted()
 * @method integer getDropped()
 * @method self   setStarted(integer $started)
 * @method self   setDropped(integer $dropped)
 */
class SpanCountEntity extends ApmRequestEntity
{
    /** @var integer $started */
    protected $started = 0;

    /** @var integer $dropped */
    protected $dropped = 0;
}
