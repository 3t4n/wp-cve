<?php

declare(strict_types=1);

namespace Siel\Acumulus\Collectors;

use Siel\Acumulus\Data\AcumulusObject;
use Siel\Acumulus\Data\AddressType;
use Siel\Acumulus\Meta;

/**
 * Collects customer data from the shop.
 *
 * Properties that are mapped:
 * - string $contactId
 * - string $contactYourId
 * - string $salutation
 * - string $website
 * - string $vatNumber
 * - string $telephone
 * - string $telephone2
 * - string $fax
 * - string $email
 * - string $bankAccountNumber
 * - string $mark
 *
 * Properties that are computed using logic:
 * - none
 *
 * Properties that are based on configuration and thus are not set here:
 * - int $type
 * - int $vatTypeId
 * - int $contactStatus
 * - int $overwriteIfExists
 * - int $disableDuplicates
 *
 * Properties that are not set:
 * - none
 *
 * Note that all address data, shipping and invoice address, are placed in
 * separate {@see \Siel\Acumulus\Data\Address} objects.
 */
abstract class CustomerCollector extends Collector
{
    /**
     * @param \Siel\Acumulus\Data\Customer $acumulusObject
     */
    protected function collectLogicFields(AcumulusObject $acumulusObject): void
    {
        parent::collectLogicFields($acumulusObject);
        $taxBasedOn = $this->getVatBasedOn();
        $acumulusObject->metadataSet(Meta::ShopVatBasedOn, $taxBasedOn);
        $taxBasedOnMapping = $this->getVatBasedOnMapping();
        $acumulusObject->setMainAddress($taxBasedOnMapping[$taxBasedOn] ?? null);
    }

    /**
     * Returns the value of the setting indicating which address is used for tax
     * calculations.
     *
     * @return string
     *   Either the (shop specific) value from the corresponding setting in the shop's
     *   config, or one of the constants {@see \Siel\Acumulus\Data\AddressType::Invoice}
     *   or {@see \Siel\Acumulus\Data\AddressType::Shipping}.
     */
    abstract protected function getVatBasedOn(): string;

    /**
     * Returns a mapping for the possible values returned by {@see getVatBasedOn} to an
     * {@see AddressType}.
     *
     * @return string[]
     *   An array with mappings for all values as may be returned by {@see getVatBasedOn}
     *   to one of the constants {@see \Siel\Acumulus\Data\AddressType::Invoice}
     *   or {@see \Siel\Acumulus\Data\AddressType::Shipping}.
     */
    protected function getVatBasedOnMapping(): array
    {
        return [
            AddressType::Shipping => AddressType::Shipping,
            AddressType::Invoice => AddressType::Invoice,
        ];
    }
}
