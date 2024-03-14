<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  Authorization
 * @package   Payever\Core
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2021 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Core\Authorization;

/**
 * Apm token service
 */
class ApmSecretService
{
    /**
     * @return string
     */
    public function get()
    {
        return null;
    }

    /**
     * @param string $apmSecret
     * @return self
     */
    public function save($apmSecret)
    {
        return $this;
    }
}
