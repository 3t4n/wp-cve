<?php

declare(strict_types=1);

namespace Siel\Acumulus\WooCommerce\Collectors;

use Siel\Acumulus\Data\AcumulusObject;
use Siel\Acumulus\Meta;

/**
 * AddressCollector for WooCommerce.
 */
class AddressCollector extends \Siel\Acumulus\Collectors\AddressCollector
{
    /**
     * @param \Siel\Acumulus\Data\Address $acumulusObject
     */
    protected function collectLogicFields(AcumulusObject $acumulusObject): void
    {
        parent::collectLogicFields($acumulusObject);
        if (!empty($acumulusObject->countryCode)) {
            /** @var \WooCommerce $woocommerce */
            global $woocommerce;
            $countries = $woocommerce->countries->get_countries();
            $acumulusObject->metadataSet(Meta::ShopCountryName, $countries[$acumulusObject->countryCode] ?? null);
        }
    }
}
