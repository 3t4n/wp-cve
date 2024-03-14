<?php

/**
 * PHP version 5.4 and 8.1
 *
 * @category  Http
 * @package   Payever\Core
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Core\Http;

use Payever\Sdk\Core\Base\MessageEntity;
use Payever\Sdk\Core\Base\MessageEntityInterface;

/**
 * Class Entity
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class ApmRequestEntity extends MessageEntity
{
    /**
     * Creates $className instance
     *
     * @param $className
     * @param $propertyValue
     *
     * @return MessageEntityInterface|mixed|null
     */
    protected function getClassInstance($className, $propertyValue)
    {
        if (!$propertyValue) {
            return null;
        }

        if ($propertyValue instanceof $className) {
            return $propertyValue;
        }

        if (is_string($propertyValue)) {
            $propertyValue = json_decode($propertyValue);
        }

        if (!is_array($propertyValue) && !is_object($propertyValue)) {
            return $this;
        }

        return new $className($propertyValue);
    }
}
