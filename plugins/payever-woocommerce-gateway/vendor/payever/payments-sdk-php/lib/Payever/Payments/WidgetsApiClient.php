<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  Payments
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments;

use Payever\Sdk\Payments\Http\RequestEntity\PaymentWidgetsRequest;
use Payever\Sdk\Payments\Http\ResponseEntity\PaymentWidgetsResponse;
use Payever\Sdk\Core\Authorization\OauthToken;
use Payever\Sdk\Core\Http\RequestBuilder;

/**
 * Class represents PaymentWidget Connector
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WidgetsApiClient extends PaymentsApiClient
{
    const URL_SANDBOX = 'https://web-widgets-backend.staging.devpayever.com/';
    const URL_LIVE    = 'https://web-widgets-backend.payever.org/';

    const FINANCE_EXPRESS_WIDGET = 'finance-express';

    const SUB_URL_GET_WIDGETS = 'api/widgets';

    /**
     * Receives list of widgets
     *
     * @param string $businessUuid
     * @param string $integration
     *
     * @return \Payever\Sdk\Core\Http\Response
     * @throws \Exception
     */
    public function getWidgets($businessUuid = '', $integration = '')
    {
        $this->configuration->assertLoaded();

        $businessUuid = $businessUuid ?: $this->getConfiguration()->getBusinessUuid();
        $integration = $integration ?: self::FINANCE_EXPRESS_WIDGET;

        $paymentWidgetsRequest = new PaymentWidgetsRequest();
        $paymentWidgetsRequest
            ->setBusinessId($businessUuid)
            ->setIntegration($integration);

        $request = RequestBuilder::post($this->getWidgetsURL())
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_INFO)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity($paymentWidgetsRequest)
            ->setResponseEntity(new PaymentWidgetsResponse())
            ->build();

        return $this->executeRequest($request, OauthToken::SCOPE_PAYMENT_INFO);
    }

    /**
     * Returns URL to get the widgets
     *
     * @return string
     */
    protected function getWidgetsURL()
    {
        return $this->getBaseEntrypoint(true) . self::SUB_URL_GET_WIDGETS;
    }
}
