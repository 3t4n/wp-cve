<?php

namespace Premmerce\WoocommerceMulticurrency\Admin\RatesUpdate;

/**
 * Currency updater interface.
 * Each updater class must represent one exchange rates data source.
 * You can use as source anything you want: any external API service, local file or your grandma's crystal ball.
 *
 * Interface RatesUpdateInterface
 * @package Premmerce\WoocommerceMulticurrency\RatesUpdate
 */
interface RatesUpdateInterface
{
    /**
     * Check if exchange rates service is available.
     * You can cache result using setCache() and getCache() methods (@see AbstractRatesUpdater class) if you want to limit service requests frequency.
     * If your currency rates source is a file, you can use file_exists() && is_readable().
     *
     * @return bool
     */
    public function isAlive();

    /**
     * Return exchange rates service name for displaying in admin area.
     *
     * @return string
     */
    public function getPublicName();

    /**
     * Return exchange rates service homepage for displaying in admin area
     *
     * @return string
     */
    public function getHomePage();

    /**
     * Return actual exchange rates for passed currencies.
     *
     * @param $currenciesList
     *
     * @return array
     */
    public function getRates($currenciesList);

    /**
     * Return array with currencies codes can be updated with this service.
     *
     * @return array
     */
    public function getAvailableCurrenciesList();
}
