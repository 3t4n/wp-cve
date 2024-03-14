<?php

/**
 * PHP version 5.4 and 8.1
 *
 * @category  RequestEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\RequestEntity;

use Payever\Sdk\Core\Http\RequestEntity;

/**
 * This class represents Payment Widgets Entity
 *
 * @method string getBusinessId()
 * @method string getIntegration()
 * @method self   setBusinessId(string $businessId)
 * @method self   setIntegration(string $integration)
 */
class PaymentWidgetsRequest extends RequestEntity
{
    const UNDERSCORE_ON_SERIALIZATION = false;

    /**
     * @var string
     */
    protected $businessId;

    /**
     * @var string
     */
    protected $integration;

    /**
     * {@inheritdoc}
     */
    public function getRequired()
    {
        return [
            'businessId',
            'integration'
        ];
    }
}
