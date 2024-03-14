<?php

if (!defined('WPINC')) {
    die;
}

/** @var \Premmerce\WoocommerceMulticurrency\WoocommerceMulticurrencyPlugin $main */
$GLOBALS['premmerce_multicurrency_api'] = $main->getApi();

/**
 * Get API class instance
 *
 * @return \Premmerce\WoocommerceMulticurrency\API\PremmerceMulticurrencyAPI
 */
function premmerce_multicurrency()
{
    return $GLOBALS['premmerce_multicurrency_api'];
}