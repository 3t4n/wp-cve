<?php

/**
 * PHP version 5.4 and 8.1
 *
 * @category  API
 * @package   Payever\Core
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Core\Enum;

use Payever\Sdk\Core\Base\EnumerableConstants;

/**
 * This class represents Apm Agent
 */
class ApmAgent extends EnumerableConstants
{
    const NAME         = 'payever-amp-php-agent';
    const VERSION      = '1.0.0';
    const MICROTIME_MULTIPLIER = 1000000;
}
