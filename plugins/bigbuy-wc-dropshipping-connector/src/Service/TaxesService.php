<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Manager\SystemManager;
use WcMipConnector\Manager\TaxesManager;

class TaxesService
{
    private const MINIMUM_TAX_RATE = 0.0;
    private const TAX_RATE_CLASS_STANDARD = 'standard';

    /**
     * @var TaxesManager
     */
    private $manager;

    public function __construct()
    {
        $this->manager = new TaxesManager();
    }

    /**
     * @return array
     */
    public function getTaxes(): array
    {
        $shopTaxes = [];
        $woocommerceDefaultCountryIsoCode = $this->getWoocommerceDefaultCountryIsoCode();
        $taxes = $this->manager->getTaxes();

        if (empty($taxes)) {
            return $shopTaxes;
        }

        foreach ($taxes as $tax) {
            if (($tax['country'] === $woocommerceDefaultCountryIsoCode) || (empty($tax['country']) && empty($tax['state']) && empty($tax['postcode']) && empty($tax['city']))) {
                $shopTax['TaxID'] = $tax['id'];
                $shopTax['Name'] = $tax['name'];
                $shopTax['Rate'] = $tax['rate'];
                $shopTaxes[] =  $shopTax;
            }
        }

        return $shopTaxes;
    }

    /**
     * @param array $taxes
     * @param int $taxId
     * @return string
     */
    public function getTaxClassByTaxId(array $taxes, int $taxId): string
    {
        if (empty($taxes)) {
            return self::TAX_RATE_CLASS_STANDARD;
        }

        foreach ($taxes as $tax) {
            if ($tax['id'] === $taxId) {
                return $tax['class'];
            }
        }

        return self::TAX_RATE_CLASS_STANDARD;
    }

    /**
     * @return string
     */
    private function getWoocommerceDefaultCountryIsoCode(): string
    {
        $systemManager = new SystemManager();
        $woocommerceDefaultCountryIsoCode = explode(':', $systemManager->getWoocommerceDefaultCountryIsoCode());
        return $woocommerceDefaultCountryIsoCode[0];
    }

    /**
     * @param string $isoCountry
     * @return array
     */
    public function getTaxWithMaxRate(string $isoCountry): array
    {
        $taxData = [];
        $taxes = $this->manager->getTaxes();
        $currentTaxRate = self::MINIMUM_TAX_RATE;

        foreach ($taxes as $tax) {
            $taxRate = $tax['rate'];
            $taxCountry = $tax['country'];
            $isGlobalTax = empty($taxCountry) && empty($tax['state']) && empty($tax['postcode']) && empty($tax['city']);

            if ($currentTaxRate <= (float)$taxRate && ($taxCountry === $isoCountry || $isGlobalTax)) {
                $taxData['Rate'] = $taxRate;
                $taxData['Id'] = $tax['id'];
                $currentTaxRate = $taxRate;
            }
        }

        if (empty($taxes) || empty($taxData)) {
            throw new \LogicException('Could not retrieve taxes from shop');
        }

        return $taxData;
    }
}