<?php

declare(strict_types=1);

namespace Siel\Acumulus\WooCommerce\Collectors;

use Siel\Acumulus\Collectors\InvoiceCollector as BaseInvoiceCollector;
use Siel\Acumulus\Data\AcumulusObject;
use Siel\Acumulus\Meta;

/**
 * InvoiceCollector for WooCommerce.
 */
class InvoiceCollector extends BaseInvoiceCollector
{
    protected function collectLogicFields(AcumulusObject $acumulusObject): void
    {
        parent::collectLogicFields($acumulusObject);
        $acumulusObject->metadataSet(Meta::PricesIncludeVat, wc_prices_include_tax());
    }

}
