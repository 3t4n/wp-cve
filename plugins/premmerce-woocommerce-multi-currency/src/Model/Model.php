<?php

namespace Premmerce\WoocommerceMulticurrency\Model;

use \WC_Product;
use \WC_Order;
use \WC_Cache_Helper;
use \wpdb;

class Model
{
    const MAIN_CURRENCY_OPTION_NAME = 'premmerce_main_currency';

    const DEFAULT_COUNTRY_CURRENCY_OPTION_NAME = 'premmerce_multicurrency_default_country_currency';

    const RATES_UPDATER_INTERVAL_OPTION_NAME = 'premmerce_multicurrency_rates_updater_frequency';

    const RATES_UPDATER_CRON_HOOK = 'premmerce_multicurrency_update_rates';

    const RATES_UPDATER_INTERVAL_NAME = 'premmerce_multicurrency_rates_update';

    const ORDER_CURRENCY_ID_KEY = '_premmerce_multicurrency_order_currency_id';

    const OLD_SHOP_CURRENCY_TRANSIENT_NAME = 'premmerce_old_shop_currency';

    /**
     *
     * @var wpdb
     */
    private $wpdb;

    /**
     * @var string
     */
    private $currenciesTable;

    /**
     * Array with countries and currencies codes. Used on currency add/edit pages to set country name when currency code selected.
     *
     * @var array
     */
    private $countriesCurrencies = array(
        'AF' => 'AFN', 'AL' => 'ALL', 'DZ' => 'DZD', 'AS' => 'USD', 'AD' => 'EUR', 'AO' => 'AOA', 'AI' => 'XCD', 'AQ' => 'XCD', 'AG' => 'XCD',
        'AR' => 'ARS', 'AM' => 'AMD', 'AW' => 'AWG', 'AU' => 'AUD', 'AT' => 'EUR', 'AZ' => 'AZN', 'BS' => 'BSD', 'BH' => 'BHD', 'BD' => 'BDT',
        'BB' => 'BBD', 'BY' => 'BYN', 'BE' => 'EUR', 'BZ' => 'BZD', 'BJ' => 'XOF', 'BM' => 'BMD', 'BT' => 'BTN', 'BO' => 'BOB', 'BA' => 'BAM',
        'BW' => 'BWP', 'BV' => 'NOK', 'BR' => 'BRL', 'IO' => 'USD', 'BN' => 'BND', 'BG' => 'BGN', 'BF' => 'XOF', 'BI' => 'BIF', 'KH' => 'KHR',
        'CM' => 'XAF', 'CA' => 'CAD', 'CV' => 'CVE', 'KY' => 'KYD', 'CF' => 'XAF', 'TD' => 'XAF', 'CL' => 'CLP', 'CN' => 'CNY', 'HK' => 'HKD',
        'CX' => 'AUD', 'CC' => 'AUD', 'CO' => 'COP', 'KM' => 'KMF', 'CG' => 'XAF', 'CD' => 'CDF', 'CK' => 'NZD', 'CR' => 'CRC', 'HR' => 'HRK',
        'CU' => 'CUP', 'CY' => 'EUR', 'CZ' => 'CZK', 'DK' => 'DKK', 'DJ' => 'DJF', 'DM' => 'XCD', 'DO' => 'DOP', 'EC' => 'ECS', 'EG' => 'EGP',
        'SV' => 'SVC', 'GQ' => 'XAF', 'ER' => 'ERN', 'EE' => 'EUR', 'ET' => 'ETB', 'FK' => 'FKP', 'FO' => 'DKK', 'FJ' => 'FJD', 'FI' => 'EUR',
        'FR' => 'EUR', 'GF' => 'EUR', 'TF' => 'EUR', 'GA' => 'XAF', 'GM' => 'GMD', 'GE' => 'GEL', 'DE' => 'EUR', 'GH' => 'GHS', 'GI' => 'GIP',
        'GR' => 'EUR', 'GL' => 'DKK', 'GD' => 'XCD', 'GP' => 'EUR', 'GU' => 'USD', 'GT' => 'QTQ', 'GG' => 'GGP', 'GN' => 'GNF', 'GW' => 'GWP',
        'GY' => 'GYD', 'HT' => 'HTG', 'HM' => 'AUD', 'HN' => 'HNL', 'HU' => 'HUF', 'IS' => 'ISK', 'IN' => 'INR', 'ID' => 'IDR', 'IR' => 'IRR',
        'IQ' => 'IQD', 'IE' => 'EUR', 'IM' => 'GBP', 'IL' => 'ILS', 'IT' => 'EUR', 'JM' => 'JMD', 'JP' => 'JPY', 'JE' => 'GBP', 'JO' => 'JOD',
        'KZ' => 'KZT', 'KE' => 'KES', 'KI' => 'AUD', 'KP' => 'KPW', 'KR' => 'KRW', 'KW' => 'KWD', 'KG' => 'KGS', 'LA' => 'LAK', 'LV' => 'EUR',
        'LB' => 'LBP', 'LS' => 'LSL', 'LR' => 'LRD', 'LY' => 'LYD', 'LI' => 'CHF', 'LT' => 'EUR', 'LU' => 'EUR', 'MK' => 'MKD', 'MG' => 'MGF',
        'MW' => 'MWK', 'MY' => 'MYR', 'MV' => 'MVR', 'ML' => 'XOF', 'MT' => 'EUR', 'MH' => 'USD', 'MQ' => 'EUR', 'MR' => 'MRO', 'MU' => 'MUR',
        'YT' => 'EUR', 'MX' => 'MXN', 'FM' => 'USD', 'MD' => 'MDL', 'MC' => 'EUR', 'MN' => 'MNT', 'ME' => 'EUR', 'MS' => 'XCD', 'MA' => 'MAD',
        'MZ' => 'MZN', 'MM' => 'MMK', 'NA' => 'NAD', 'NR' => 'AUD', 'NP' => 'NPR', 'NL' => 'EUR', 'AN' => 'ANG', 'NC' => 'XPF', 'NZ' => 'NZD',
        'NI' => 'NIO', 'NE' => 'XOF', 'NG' => 'NGN', 'NU' => 'NZD', 'NF' => 'AUD', 'MP' => 'USD', 'NO' => 'NOK', 'OM' => 'OMR', 'PK' => 'PKR',
        'PW' => 'USD', 'PA' => 'PAB', 'PG' => 'PGK', 'PY' => 'PYG', 'PE' => 'PEN', 'PH' => 'PHP', 'PN' => 'NZD', 'PL' => 'PLN', 'PT' => 'EUR',
        'PR' => 'USD', 'QA' => 'QAR', 'RE' => 'EUR', 'RO' => 'RON', 'RU' => 'RUB', 'RW' => 'RWF', 'SH' => 'SHP', 'KN' => 'XCD', 'LC' => 'XCD',
        'PM' => 'EUR', 'VC' => 'XCD', 'WS' => 'WST', 'SM' => 'EUR', 'ST' => 'STD', 'SA' => 'SAR', 'SN' => 'XOF', 'RS' => 'RSD', 'SC' => 'SCR',
        'SL' => 'SLL', 'SG' => 'SGD', 'SK' => 'EUR', 'SI' => 'EUR', 'SB' => 'SBD', 'SO' => 'SOS', 'ZA' => 'ZAR', 'GS' => 'GBP', 'SS' => 'SSP',
        'ES' => 'EUR', 'LK' => 'LKR', 'SD' => 'SDG', 'SR' => 'SRD', 'SJ' => 'NOK', 'SZ' => 'SZL', 'SE' => 'SEK', 'CH' => 'CHF', 'SY' => 'SYP',
        'TW' => 'TWD', 'TJ' => 'TJS', 'TZ' => 'TZS', 'TH' => 'THB', 'TG' => 'XOF', 'TK' => 'NZD', 'TO' => 'TOP', 'TT' => 'TTD', 'TN' => 'TND',
        'TR' => 'TRY', 'TM' => 'TMT', 'TC' => 'USD', 'TV' => 'AUD', 'UG' => 'UGX', 'UA' => 'UAH', 'AE' => 'AED', 'GB' => 'GBP', 'US' => 'USD',
        'UM' => 'USD', 'UY' => 'UYU', 'UZ' => 'UZS', 'VU' => 'VUV', 'VE' => 'VEF', 'VN' => 'VND', 'VI' => 'USD', 'WF' => 'XPF', 'EH' => 'MAD',
        'YE' => 'YER', 'ZM' => 'ZMW', 'ZW' => 'ZWD'
    );

    /**
     *
     * Model constructor.
     *
     * @global wpdb $wpdb
     *
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->currenciesTable = $this->wpdb->prefix . 'premmerce_currencies';
    }



    /***********************************************************************************************************************
     *
     * Get data methods
     *
     ***********************************************************************************************************************/

    /**
     * Returns multidimensional array with currencies added by user
     *
     * @param $frontDisplayableOnly
     *
     * @return array
     */
    public function getCurrencies($frontDisplayableOnly = false)
    {
        $cacheName = $frontDisplayableOnly ? 'currencies_front' : 'currencies_all';
        $currencies = wp_cache_get($cacheName, 'premmerce_multicurrency');

        if (!$currencies) {
            $table = $this->wpdb->prefix . 'premmerce_currencies';
            $sql = "SELECT * FROM {$table}";
            if ($frontDisplayableOnly) {
                $sql .= " WHERE display_on_front = 1";
            }
            $currenciesRaw = $this->wpdb->get_results($sql, ARRAY_A);


            foreach ($currenciesRaw as $currencyIndex => $currency) {
                $currencies[$currency['id']] = $currency;
            }

            wp_cache_set($cacheName, $currencies, 'premmerce_multicurrency');
        }


        return apply_filters('premmerce_multicurrency_get_currencies', $currencies, $frontDisplayableOnly);
    }

    /**
     * Returns array with main currency data
     *
     * @return array $mainCurrency
     */
    public function getMainCurrency()
    {
        $mainCurrencyId = $this->getMainCurrencyId();
        return $this->getCurrencies()[$mainCurrencyId];
    }

    /**
     * Return shop main currency id
     *
     * @return string
     */
    public function getMainCurrencyId()
    {
        return get_option(self::MAIN_CURRENCY_OPTION_NAME, 1);
    }

    /**
     * @return array
     */
    public function getCountriesCurrenciesCodes()
    {
        return apply_filters(
            'premmerce_multicurrency_geolocation_countries_and_currencies',
            $this->countriesCurrencies
        );
    }

    /**
     * @param $currencyId
     *
     * @return string
     */
    public function getUpdaterIdForCurrency($currencyId)
    {
        $updaterName = '';
        $currencies = $this->getCurrencies();
        if ($currencyId && isset($currencies[$currencyId]['updater'])) {
            $updaterName = $currencies[$currencyId]['updater'];
        }

        return $updaterName;
    }

    /**
     * Get row from currencies table with given id
     *
     * @param $id
     *
     * @return array|false
     */
    public function getCurrencyById($id)
    {
        $currency = isset($this->getCurrencies()[$id]) ? $this->getCurrencies()[$id] : false;

        return apply_filters('premmerce_multicurrency_currency_by_id', $currency);
    }

    /**
     * Get price format for currency id
     *
     * @param $currencyId
     *
     * @return string
     */
    public function getPriceFormat($currencyId)
    {
        $priceFormats = array(
            'left' => '%1$s%2$s',
            'right' => '%2$s%1$s',
            'left_space' => '%1$s&nbsp;%2$s',
            'right_space' => '%2$s&nbsp;%1$s'
        );

        $position = self::getCurrencies()[$currencyId]['position'];

        return $priceFormats[$position];
    }

    /**
     * Get posts IDs by parameters. Grouped products excluded.
     *
     * @param int $currencyId
     * @param int $productsNumber
     * @param int $offset
     *
     * @return array
     */
    public function getProductsIDs($currencyId, $productsNumber = -1, $offset = 0)
    {
        $args = array(
            'post_type' => 'product',
            'numberposts' => $productsNumber,
            'fields' => 'ids',
            'offset' => $offset,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_type',
                    'field' => 'slug',
                    'terms' => 'grouped',
                    'operator' => 'NOT IN'
                )
            )
        );

        if ($currencyId) {
            $args['meta_query'][0]['value'] = $currencyId;
            $args['meta_query'][0]['key'] = '_product_currency';
        }

        return get_posts($args);
    }

    /**
     * Return product currency.
     *
     * @param $productObject
     *
     * @return string $productCurrency
     *
     */
    public function getProductCurrency(WC_Product $productObject)
    {
        $product = $productObject->is_type('variation') ? wc_get_product($productObject->get_parent_id()) : $productObject;
        $productCurrency = $product->get_meta('_product_currency') ?: $this->getMainCurrencyId();
        return apply_filters('premmerce_multicurrency_get_product_currency', $productCurrency, $productObject);
    }

    /**
     * Return product price in product currency.
     *
     * @param WC_Product $product
     * @param string $priceType
     *
     * @return string
     *
     */
    public function getProductPriceInProductCurrency(WC_Product $product, $priceType)
    {
        $key = '_product_currency_' . $priceType . '_price';

        if ($product->meta_exists($key)) {
            $price = $product->get_meta($key);
        } else {
            $price = 'sale' == $priceType ? $product->get_sale_price('edit') : $product->get_regular_price('edit');
        }

        return apply_filters('premmerce_multicurrency_get_product_price_in_product_currency', $price, $product, $priceType);
    }

    /**
     * @return int
     */
    public function getRatesUpdaterCronScheduleTime()
    {
        $interval = wp_get_schedules()[self::RATES_UPDATER_INTERVAL_NAME]['interval'];
        return isset($interval) ? (int)$interval : 0;
    }

    /**
     * Check if plugin table exists
     *
     * @return bool
     */
    public function pluginTableExists()
    {
        return $this->wpdb->get_var("SHOW TABLES LIKE '" . $this->currenciesTable . "'") === $this->currenciesTable;
    }

    /**
     * Check if currency with passed $id exists in plugin table
     *
     * @param $id
     *
     * @return bool
     *
     */
    public function currencyExists($id)
    {
        return (bool)$this->wpdb->get_var($this->wpdb->prepare(
            "SELECT EXISTS(SELECT id FROM {$this->currenciesTable} WHERE id = '%d')",
            $id
        ));
    }

    /**
     * @return array
     */
    private function getWoocommerceCurrencyData()
    {
        $shopCurrencyCode = get_option('woocommerce_currency');
        $currencySymbol = function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol($shopCurrencyCode) : '';

        $defaultCurrency = array(
            'currency_name' => $shopCurrencyCode,
            'code' => $shopCurrencyCode,
            'rate' => (float)1,
            'symbol' => $currencySymbol,
            'position' => get_option('woocommerce_currency_pos'),
            'decimal_separator' => get_option('woocommerce_price_decimal_sep'),
            'thousand_separator' => get_option('woocommerce_price_thousand_sep'),
            'decimals_num' => get_option('woocommerce_price_num_decimals'),
            'display_on_front' => true
        );

        return apply_filters('premmerce_multicurrency_get_woocommerce_currency_data', $defaultCurrency);
    }

    /**
     * Return array with currencies should be updated. Keys is ids, values is codes.
     *
     * @return array
     */
    public function getCurrenciesToUpdateRates()
    {
        $allCurrencies = $this->getCurrencies();
        $mainCurrencyCode = self::getMainCurrency()['code'];
        $toUpdate = array_map(function ($currency) use ($mainCurrencyCode) {
            if ($currency['code'] !== $mainCurrencyCode && isset($currency['updater']) && $currency['updater']) {
                return $currency['code'];
            }
        }, $allCurrencies);

        $toUpdate = array_filter($toUpdate);
        $toUpdate = apply_filters('premmerce_multicurrency_currencies_to_update_rates', $toUpdate);
        return $toUpdate;
    }

    /**
     * Convert price string with defined by user decimal delimiter
     *
     * @param string $priceString
     * @param string $decimalSeparator
     * @param int    $decimalsNumber
     *
     * @return float
     *
     * @todo: think about using this in all places instead of just wc_format_decimal().
     */
    public function priceStringToFloat($priceString, $decimalSeparator = null, $decimalsNumber = null)
    {
        $defaultSeparator = wc_get_price_decimal_separator();
        $replaceDecimalSeparator = function () use ($decimalSeparator, $defaultSeparator) {
            return $decimalSeparator ?: $defaultSeparator;
        };

        $decimalsNumber = $decimalsNumber ?: wc_get_price_decimals();


        add_filter('wc_get_price_decimal_separator', $replaceDecimalSeparator);
        $price = (float) wc_format_decimal($priceString, $decimalsNumber, true);
        remove_filter('wc_get_price_separator', $replaceDecimalSeparator);

        return $price;
    }

    /**
     * Get price from $_POST for $variationID
     *
     *
     * @param $variationID
     * @param $priceType
     *
     * @return string|null
     *
     */
    private function getVariationPriceFromPost($variationID, $priceType)
    {
        if (!isset($_POST['variable_post_id'])) {
            return null;
        }

        $variationArrayIndex = array_search($variationID, $_POST['variable_post_id']);

        return $_POST['premmerce_variable_' . $priceType . '_price'][$variationArrayIndex];
    }

    /**
     * Return true if one or any currencies is allowed to display on front. Return false, if available more than one.
     *
     * @param int $currencyIdToCheckFor
     *
     * @return bool
     */
    private function isLastAvailableForUsersCurrency($currencyIdToCheckFor)
    {
        $sql = "SELECT COUNT(*) FROM $this->currenciesTable WHERE `display_on_front` = %d AND `id` != %d";
        $result = $this->wpdb->get_var($this->wpdb->prepare($sql, 1, $currencyIdToCheckFor));

        return ! boolval($result);
    }

    /***********************************************************************************************************************
     *
     * Insert/update methods
     *
     ***********************************************************************************************************************/
    /**
     * Update currency or add new
     *
     * @param   array  $newCurrency
     *
     * @return  Result $result
     */
    public function insertCurrencyData($newCurrency)
    {
        unset($newCurrency['last_update']);

        if (0 >= $newCurrency['rate']) {
            $result = new Result('insert_currency_data');
            $result->setMessage(
                __('Currency rate must be more than zero.', 'premmerce-woocommerce-multicurrency'),
                'error'
            );
            $result->setSuccess(false);

            return $result;
        }

        do_action('premmerce_multicurrency_before_insert_currency_data', $newCurrency);

        $result = $newCurrency['id'] ? $this->updateExistingCurrency($newCurrency) : $this->insertNewCurrency($newCurrency);

        do_action('premmerce_multicurrency_after_insert_currency_data', $newCurrency, $result);

        if ($result->getSuccess()) {
            //Invalidate WC prices cache when updating currency data
            $this->cleanCache();
            $this->invalidateWcProductPricesCache();

            if (isset($_POST['currency-countries'])) {
                $id = $newCurrency['id'] ?: $this->wpdb->insert_id;

                $this->saveDefaultCurrencyCountries($id);
            }
        }

        return $result;
    }

    /**
     * @param int $interval
     */
    public function addCronSchedule($interval = 0)
    {
        $interval = $interval ?: get_option(self::RATES_UPDATER_INTERVAL_OPTION_NAME);

        add_filter('cron_schedules', function ($schedules) use ($interval) {
            $schedules[self::RATES_UPDATER_INTERVAL_NAME] = array(
                'interval' => $interval,
                'display' => 'Premmerce multicurrency update rates'
            );

            return $schedules;
        });
    }

    /**
     * @param array     $newCurrencyData
     * @return Result   $result
     */
    private function insertNewCurrency($newCurrencyData)
    {
        $result = new Result('insert_new_currency');

        $newCurrencyData = apply_filters('premmerce_multicurrency_new_currency_data', $newCurrencyData);
        $format = $this->getPlaceholdersForCurrencyInsert($newCurrencyData);
        $insertResult = $this->wpdb->insert($this->currenciesTable, $newCurrencyData, $format);
        $success = false !== $insertResult;

        if ($success) {
            $message        = __('Currency was successfully added.', 'premmerce-woocommerce-multicurrency');
            $messageType    = 'success';
        } else {
            $message        = __('Something goes wrong. Currency data wasn\'t saved.', 'premmerce-woocommerce-multicurrency');
            $messageType    = 'error';
        }

        $result->setMessage($message, $messageType);
        $result->setSuccess($success);

        return $result;
    }

    /**
     * @param $currencyData
     * @return Result
     */
    private function updateExistingCurrency($currencyData)
    {
        $result = new Result('update_existing_currency');

        $id = $currencyData['id'];

        if ($id == $this->getMainCurrencyId() && 1 != $currencyData['rate']) {
            $result->setMessage(
                __('Main currency rate must be equal to 1 and cannot be changed', 'premmerce-woocommerce-multicurrency'),
                'error'
            );
            $result->setSuccess(false);
        } else {
            unset($currencyData['id']);
            $currencyData = apply_filters('premmerce_multicurrency_update_currency_data', $currencyData, $id);

            if (!$currencyData['display_on_front'] && $this->isLastAvailableForUsersCurrency($id)) {
                $result->setMessage(
                    __(
                        'You are trying to set this currency unavailable for users, but this is last available currency. At least one currency must be available.',
                        'premmerce-woocommerce-multicurrency'
                    ),
                    'error'
                );
                $result->setSuccess(false);

                return $result;
            }

            $where = array('id' => $id);
            $format = $this->getPlaceholdersForCurrencyInsert($currencyData);
            $updateResult = $this->wpdb->update($this->currenciesTable, $currencyData, $where, $format);
            $success = (false !== $updateResult);

            if ($success) {
                $message        = __('Currency was successfully updated.', 'premmerce-woocommerce-multicurrency');
                $messageType    = 'success';
            } else {
                $message        = __('Something goes wrong. Currency data wasn\'t saved.', 'premmerce-woocommerce-multicurrency');
                $messageType    = 'error';
            }

            $result->setMessage($message, $messageType);
            $result->setSuccess($success);
        }

        return $result;
    }

    /**
     * Get placeholders before currency insertion
     *
     * @param $currencyData
     *
     * @return array
     */
    private function getPlaceholdersForCurrencyInsert($currencyData)
    {
        $placeholdersMap = array(
            'id' => '%d',
            'currency_name' => '%s',
            'code' => '%s',
            'rate' => '%f',
            'symbol' => '%s',
            'position' => '%s',
            'decimal_separator' => '%s',
            'thousand_separator' => '%s',
            'decimals_num' => '%d',
            'display_on_front' => '%d',
            'auto_update' => '%d',
            'updater' => '%s',
            'last_update' => '%s'
        );

        $placeholders = array();

        foreach ($currencyData as $fieldName => $fieldValue) {
            $placeholders[] = $placeholdersMap[$fieldName];
        }

        return $placeholders;
    }

    /**
     *  Add currency id to order post meta
     *
     * @param WC_Order $order
     * @param string    $userCurrencyId
     *
     */
    public function setOrderCurrencyId(WC_Order $order, $userCurrencyId)
    {
        $order->update_meta_data(self::ORDER_CURRENCY_ID_KEY, $userCurrencyId);
        $order->save();
        do_action(
            'premmerce_multicurrency_order_currency_id_updated',
            $order->get_id(),
            $userCurrencyId,
            self::ORDER_CURRENCY_ID_KEY
        );
    }

    /**
     * Return order currency id
     *
     * @param $orderId
     *
     * @return string
     *
     *
     */
    public function getOrderCurrencyId($orderId)
    {
        $order = wc_get_order($orderId);
        return apply_filters('premmerce_multicurrency_get_order_currency_id', $order->get_meta(self::ORDER_CURRENCY_ID_KEY));
    }

    /**
     * Save default country currency option
     *
     * @param $currencyId
     */
    private function saveDefaultCurrencyCountries($currencyId)
    {
        $countriesList = array();
        
        switch ($_POST['currency-countries']) {
            case 'include':
            case 'except':
                $type            = $_POST['currency-countries'];
                $postCountryList = isset($_POST['currency-countries-list']) ? $_POST['currency-countries-list'] : array();
                $countriesList   = array_map('esc_sql', $postCountryList);
                break;

            case 'all':
                $type = 'all';
                break;

            default:
                $type = '';
        }

        if (empty($countriesList) && !$type) {
            return;
        }


        $currencyCountrySettings = array(
            'countries' => $countriesList,
            'type' => $type
        );

        //If we set one currency as default for all countries, another currencies can't be used as default for any country.
        if ('all' === $type) {
            $optionData = array();
        } else {
            $optionData = get_option(Model::DEFAULT_COUNTRY_CURRENCY_OPTION_NAME, array());


            foreach ($optionData as $id => $currencyCountries) {
                if ($currencyCountries['type'] === 'all') {
                    $optionData[$id]['type'] = 'include';
                    $optionData[$id]['countries'] = array();
                } elseif (array_intersect($currencyCountries['countries'], $countriesList)) {
                    $optionData[$id]['countries'] = array_diff($currencyCountries['countries'], $countriesList);
                }
            }
        }


        $optionData[$currencyId] = $currencyCountrySettings;
        update_option(Model::DEFAULT_COUNTRY_CURRENCY_OPTION_NAME, $optionData);
    }

    /**
     * Update currencies rates
     *
     * @param array $data Currencies ids as keys, new rates as values
     */
    public function setNewRates($data)
    {
        foreach ($data as $currencyId => $currencyRate) {
            $currencyData = $this->getCurrencyById($currencyId);
            $currencyData['rate'] = $currencyRate;
            $this->insertCurrencyData($currencyData);
        }

        do_action('premmerce_multicurrency_rates_updated', $data);
    }

    /**
     * Recalculate currencies rates related to main currency
     *
     * @param int $newMainCurrencyId
     */
    public function recalculateRates($newMainCurrencyId)
    {
        $currenciesArray = $this->getCurrencies();
        $newMainCurrencyRate = $currenciesArray[$newMainCurrencyId]['rate'];

        $oldMainCurrencyRateToNewCurrencyRate = 1 / $newMainCurrencyRate;

        do_action('premmerce_multicurrency_before_recalculate_rates');

        set_transient(self::OLD_SHOP_CURRENCY_TRANSIENT_NAME, get_option(self::MAIN_CURRENCY_OPTION_NAME), DAY_IN_SECONDS);

        update_option('woocommerce_currency', $currenciesArray[$newMainCurrencyId]['code']);
        update_option(Model::MAIN_CURRENCY_OPTION_NAME, $newMainCurrencyId);

        $newRates = array();
        foreach ($currenciesArray as $currencyId => $currencyData) {
            //We don't recalculate new main currency rate. It must be 1, but sometimes we get 0.9999999999.
            // This happens because we operate with floats. So, we just manually set (double) 1 as main currency rate.
            if ($currencyId == $newMainCurrencyId) {
                $newRates[$currencyId] = (double) 1;
            } else {
                $newRates[$currencyId] = (double) $currencyData['rate'] * $oldMainCurrencyRateToNewCurrencyRate;
            }
        }

        $this->setNewRates($newRates);
        do_action('premmerce_multicurrency_after_recalculate_rates', $newRates);
    }

    /**
     * Update product with given id. Add meta fields if needed, recalculate prices.
     *
     * @param $id
     */
    public function updateProductData($id)
    {
        $productObject = wc_get_product($id);

        do_action('premmerce_multicurrency_before_update_product_data', $id, $productObject);


        $productCurrency = $this->getProductCurrency($productObject);
        $postWithCurrencyFieldId = $productObject->is_type('variation') ? $productObject->get_parent_id() : $productObject->get_id();
        $currencyFieldExists = metadata_exists('post', $postWithCurrencyFieldId, '_product_currency');

        if (!$currencyFieldExists || !$this->currencyExists($productCurrency)) {
            $this->addProductMetaFields($productObject);
        }


        if ($productObject->is_type('variable')) {
            $children = $productObject->get_children();
            foreach ($children as $childId) {
                $productVariation = wc_get_product($childId);
                $this->recalculateProductPrices($productVariation);
            }
        } else {
            $this->recalculateProductPrices($productObject);
        }

        do_action('premmerce_multicurrency_after_update_product_data', $id, $productObject);
    }

    /**
     * Add _product_currency, _product_currency_regular_price and _product_currency_sale price fields to product and it's children.
     *
     * @param WC_Product $product
     *
     * @return array $addedFields
     *
     */
    private function addProductMetaFields(WC_Product $product)
    {
        $currencyId = get_transient(self::OLD_SHOP_CURRENCY_TRANSIENT_NAME);
        if (!$this->currencyExists($currencyId)) {
            $currencyId = get_option(Model::MAIN_CURRENCY_OPTION_NAME);
        }

        $product->update_meta_data('_product_currency', $currencyId);

        $addedFields = array('currency' => $currencyId);

        if ($product->is_type('variable')) {
            $children = $product->get_children();
            foreach ($children as $childID) {
                $childProductObject = wc_get_product($childID);

                $regularPrice = $childProductObject->get_regular_price('edit');
                $salePrice = $childProductObject->get_sale_price('edit');

                $addedFields['childrenPrices'][$childID]['regular'] = $regularPrice;
                $addedFields['childrenPrices'][$childID]['sale'] = $salePrice;

                $this->setProductPricesInProductCurrency($childProductObject, $regularPrice, $salePrice);
            }
        } else {
            $regularPrice = $product->get_regular_price('edit');
            $salePrice = $product->get_sale_price('edit');

            $addedFields['regular'] = $regularPrice;
            $addedFields['sale'] = $salePrice;

            $this->setProductPricesInProductCurrency($product, $regularPrice, $salePrice);
        }

        return $addedFields;
    }

    /**
     * Recalculate native price fields for given product based on it's _product_currency_*_price fields
     *
     * @param WC_Product $product
     */
    public function recalculateProductPrices(WC_Product $product)
    {
        do_action('premmerce_multicurrency_before_recalculate_product_prices', $product);

        $productCurrency = $this->getProductCurrency($product);

        $currencies = $this->getCurrencies();
        $productCurrencyRate = $currencies[$productCurrency]['rate'];
        $productCurrencyDecimalsNum = $currencies[$productCurrency]['decimals_num'];


        $productCurrencyRegularPrice = (float) $this->getProductPriceInProductCurrency($product, 'regular');
        $productCurrencySalePrice = (float) $this->getProductPriceInProductCurrency($product, 'sale');

        $productNewRegularPrice = round(
            $productCurrencyRegularPrice * $productCurrencyRate,
            $productCurrencyDecimalsNum
        );
        $productNewSalePrice = round($productCurrencySalePrice * $productCurrencyRate, $productCurrencyDecimalsNum);


        if ($product->meta_exists('_product_currency_regular_price')) {
            $product->set_regular_price($productNewRegularPrice ?: '');
        }

        $product->set_sale_price($productNewSalePrice ?: '');

        $product->save();

        do_action('premmerce_multicurrency_after_recalculate_product_prices', $product);
    }

    /**
     * Save product currency and price.
     *
     * @param $postId
     * @param $variationNumber
     *
     * @todo: take a closer look at this method. Rewrite direct usages of $_POST (use filter_input() instead). Maybe use native WC methods to save this data.
     *
     */
    public function saveProductCustomFields($postId, $variationNumber = null)
    {
        $product = wc_get_product($postId);

        $currencyFromPost = filter_input(INPUT_POST, 'product_currency', FILTER_SANITIZE_NUMBER_INT);
        $currencyID = (string) $currencyFromPost ?: $this->getMainCurrencyId();

        if (isset($variationNumber)) {
            $date['from'] = intval(strtotime($_POST['_premmerce_sale_price_dates_from'][$variationNumber])) ?: '';
            $date['to'] = intval(strtotime($_POST['_premmerce_sale_price_dates_to'][$variationNumber])) ?: '';
        } else {
            $date['from'] = intval(strtotime($_POST['_premmerce_sale_price_dates_from'])) ?: '';
            $date['to'] = intval(strtotime($_POST['_premmerce_sale_price_dates_to'])) ?: '';
        }

        $product->set_date_on_sale_from($date['from']);
        $product->set_date_on_sale_to($date['to']);


        if ($product->is_type('grouped')) {
            return;
        }


        if (!$product->is_type('variation')) {
            $product->update_meta_data('_product_currency', $currencyID);
        }


        if ($product->is_type('variable')) {
            $variationsIds = $product->get_children();
            foreach ($variationsIds as $variationId) {
                $this->updateProductPrices(wc_get_product($variationId), $currencyFromPost);
            }

            $product->save();
            return;
        }

        $this->updateProductPrices($product, $currencyFromPost);

        $product->save();
    }

    /**
     * Update prices for product $product. Add or update _product_currency_*_price, then recalculate native price fields.
     *
     * @param WC_Product $product
     * @param string $currencyId
     */
    private function updateProductPrices(WC_Product $product, $currencyId)
    {
        $productID = $product->get_id();

        if ($product->is_type('variation')) {
            $productCurrencyRegularPrice = $this->getVariationPriceFromPost($productID, 'regular');
            $productCurrencySalePrice = $this->getVariationPriceFromPost($productID, 'sale');
        } else {
            $productCurrencyRegularPrice = $_POST['_regular_price'];
            $productCurrencySalePrice = $_POST['premmerce__sale_price'] ?: '';
        }

        if (null !== $productCurrencyRegularPrice) {
            if ($productCurrencyRegularPrice) {
                $productCurrency = $this->getCurrencyById($currencyId);
                $productCurrencyRegularPrice = $this->priceStringToFloat($productCurrencyRegularPrice, $productCurrency['decimal_separator'], $productCurrency['decimals_num']);
                $productCurrencySalePrice = $this->priceStringToFloat($productCurrencySalePrice, $productCurrency['decimal_separator'], $productCurrency['decimals_num']);
            }
            $this->setProductPricesInProductCurrency($product, $productCurrencyRegularPrice, $productCurrencySalePrice);
        }

        $this->recalculateProductPrices($product);
    }

    /**
     * Set regular or sale price for all variations of passed products id.
     *
     * @param $parentID
     * @param $value
     * @param $priceType
     *
     *
     */
    public function bulkActionsUpdateVariablePrice($parentID, $value, $priceType)
    {
        $newPrice = floatval($value); //todo: use wc_format_decimal() instead floatval() to prevent bugs with decimal separators on different locales.
        $parentObj = wc_get_product($parentID);
        $childrenIDs = $parentObj->get_children();

        foreach ($childrenIDs as $variationID) {
            $variationObj = wc_get_product($variationID);
            $prices = $this->bulkActionsPrepareProductPricesToSet($variationObj, $newPrice, $priceType);

            $this->setProductPricesInProductCurrency($variationObj, $prices['regular'], $prices['sale']);
            $this->recalculateProductPrices($variationObj);
        }
    }

    /**
     * Wrapper for method increaseOrDecreaseProductPrice(). Runs this method for given product and all for all it's children.
     *
     * @param $productID
     * @param $value
     * @param $priceType
     * @param $action
     */
    public function increaseOrDecreasePrice($productID, $value, $priceType, $action)
    {
        $product = wc_get_product($productID);
        $productCurrency = $this->getProductCurrency($product);
        if ($product->is_type('variable')) {
            $childrenIDs = $product->get_children();
            foreach ($childrenIDs as $childID) {
                $this->increaseOrDecreaseProductPrice($childID, $value, $priceType, $action, $productCurrency);
            }
        } else {
            $this->increaseOrDecreaseProductPrice($productID, $value, $priceType, $action, $productCurrency);
        }
    }

    /**
     * Increase or decrease product type by numeric value or %.
     *
     *
     * @param $productID
     * @param $value
     * @param $priceType
     * @param $action
     * @param $productCurrency
     *
     */
    public function increaseOrDecreaseProductPrice($productID, $value, $priceType, $action, $productCurrency)
    {
        $productObject = wc_get_product($productID);
        $priceFieldName = '_product_currency_' . $priceType . '_price';


        if ('set_sale_decreased_by_regular' === $action) {
            $operator = '-';

            $price = $productObject->get_meta('_product_currency_regular_price');
        } else {
            $operator = ('decrease' === $action) ? '-' : '+';
            $price = $productObject->get_meta($priceFieldName);
        }


        if ('%' === substr($value, -1)) {
            $percent = wc_format_decimal(substr($value, 0, -1));
            $price += ($price / 100 * $percent) * "{$operator}1";
        } else {
            $price += $value * "{$operator}1";
        }


        $price = $this->roundPriceByCurrencySettings($price, $productCurrency);

        $prices = $this->bulkActionsPrepareProductPricesToSet($productObject, $price, $priceType);

        $this->setProductPricesInProductCurrency($productObject, $prices['regular'], $prices['sale']);

        $this->recalculateProductPrices($productObject);
    }

    /**
     * Update prices for several products.
     *
     *
     * @param WC_Product $productObject
     * @param $newValue
     * @param $priceField
     * @param string $action
     *
     */
    public function bulkActionsUpdatePrices(WC_Product $productObject, $newValue, $priceField, $action = 'set')
    {
        if ($productObject->is_type('grouped')) {
            return;
        }

        $value = ('set' === $action) ? $newValue : str_replace('%', '', $newValue);
        $value = (float) wc_format_decimal($value);

        $id = $productObject->get_id();

        $productCurrency = $this->getProductCurrency($productObject);

        if ($productObject->is_type('variable')) {
            $childIds = $productObject->get_children();
            foreach ($childIds as $childId) {
                if ('set' === $action) {
                    $childObject = wc_get_product($childId);

                    $prices = $this->bulkActionsPrepareProductPricesToSet($childObject, $value, $priceField);
                    $this->setProductPricesInProductCurrency($childObject, $prices['regular'], $prices['sale']);
                    $this->recalculateProductPrices($childObject);
                } else {
                    $this->increaseOrDecreaseProductPrice($childId, $newValue, $priceField, $action, $productCurrency);
                }
            }
        } else {
            if ('set' === $action) {
                $prices = $this->bulkActionsPrepareProductPricesToSet($productObject, $value, $priceField);
                $this->setProductPricesInProductCurrency($productObject, $prices['regular'], $prices['sale']);
            } else {
                $this->increaseOrDecreaseProductPrice($id, $newValue, $priceField, $action, $productCurrency);
            }
            $this->recalculateProductPrices($productObject);

            $this->updateProductData($productObject->get_id());
        }
    }

    /**
     * @param WC_Product $product
     * @param $price
     * @param $priceType
     * @return array
     */
    private function bulkActionsPrepareProductPricesToSet(WC_Product $product, $price, $priceType)
    {
        $prices = array();

        if ('regular' === $priceType) {
            $prices['regular'] = $price;
            $prices['sale'] = $this->getProductPriceInProductCurrency($product, 'sale');
        } else {
            $prices['regular'] = $this->getProductPriceInProductCurrency($product, 'regular');
            $prices['sale'] = $price;
        }

        foreach ($prices as $priceType => $priceValue) {
            $prices[$priceType] = wc_format_decimal($priceValue);
        }

        if (! $prices['sale'] || (float) $prices['sale'] >= (float) $prices['regular']) {
            $prices['sale'] = '';
        }

        return $prices;
    }

    /**
     * Change currency for product
     *
     * @param WC_Product $productObject
     * @param $newCurrency
     *
     */
    public function changeProductCurrency(WC_Product $productObject, $newCurrency)
    {
        $newCurrency = sanitize_text_field($newCurrency);

        $productObject->update_meta_data('_product_currency', $newCurrency);

        if ($productObject->is_type('variable')) {
            $children = $productObject->get_children();
            foreach ($children as $child) {
                $childProduct = wc_get_product($child);
                $this->recalculateProductPrices($childProduct);
            }
        } else {
            $this->recalculateProductPrices($productObject);
        }

        $productObject->save();
    }

    /**
     * Update product prices in product currency
     *
     * @param WC_Product       $product
     * @param                   $newRegularPrice
     * @param                   $newSalePrice
     *
     */
    public function setProductPricesInProductCurrency(WC_Product $product, $newRegularPrice, $newSalePrice = '')
    {
        $newRegularPrice = wc_format_decimal($newRegularPrice);
        $newSalePrice = wc_format_decimal($newSalePrice);

        $product->update_meta_data('_product_currency_regular_price', $newRegularPrice ?: '');
        $product->update_meta_data('_product_currency_sale_price', $newSalePrice ?: '');
    }

    /**
     * Round given price value according to currency decimals number setting
     * Default number of digits after comma is 2.
     *
     * @param $priceValue
     * @param $currency
     *
     * @return float
     */
    public function roundPriceByCurrencySettings($priceValue, $currency)
    {
        $priceValue = (float) wc_format_decimal($priceValue);
        $decimalsNumber = $this->getCurrencies()[$currency]['decimals_num'] ?: 2;
        return round($priceValue, $decimalsNumber);
    }

    /**
     * Fill main shop currency data from Woocommerce options.
     * Fired only if plugin was first time activated without Woocommerce and then Woocommerce was enabled.
     */
    public function fillMainCurrencyData()
    {
        $data = $this->getWoocommerceCurrencyData();
        $format = array('%s', '%s', '%f', '%s', '%s', '%s', '%s', '%d');
        $mainCurrencyId = (int)get_option(self::MAIN_CURRENCY_OPTION_NAME);
        $this->wpdb->update($this->currenciesTable, $data, array('id' => $mainCurrencyId), $format);
    }

    /**
     *  Create table with currencies and fill with default shop currency data
     */
    public function updateDB()
    {
        $charsetCollate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE " . $this->currenciesTable . " (
					  `id` SMALLINT(20) NOT NULL AUTO_INCREMENT,
					  `currency_name` VARCHAR(255),
					  `code` VARCHAR(255),
					  `rate` DOUBLE,
					  `symbol` VARCHAR(255),
					  `position` VARCHAR (255),
					  `decimal_separator` VARCHAR (255),
					  `thousand_separator` VARCHAR (255),
					  `decimals_num` TINYINT,
					  `display_on_front` BOOL DEFAULT 0,
					  `auto_update` BOOL DEFAULT 0,
					  `updater` VARCHAR(255),
					  `last_update` TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
					  PRIMARY KEY  (`id`)
					) $charsetCollate";


        if (!function_exists('dbDelta')) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        }


        dbDelta($sql);

        if (!$this->getCurrencies()) {
            $defaultCurrency = $this->getWoocommerceCurrencyData();
            $this->wpdb->insert(
                $this->currenciesTable,
                $defaultCurrency,
                array('%s', '%s', '%f', '%s', '%s', '%s', '%s', '%d')
            );
            update_option(self::MAIN_CURRENCY_OPTION_NAME, $this->wpdb->insert_id);
        }


        $this->cleanCache();
        $this->invalidateWcProductPricesCache();
    }

    /**
     * Force Woocommerce to invalidate all products prices transients
     */
    public function invalidateWcProductPricesCache()
    {
        if (class_exists(WC_Cache_Helper::class)) {
            WC_Cache_Helper::get_transient_version('product', true);
        }
    }




    /***********************************************************************************************************************
     *
     * Uninstall/remove currency methods
     *
     ***********************************************************************************************************************/

    /**
     * Delete currencies cashes
     *
     */
    public function cleanCache()
    {
        wp_cache_delete('currencies_front', 'premmerce_multicurrency');
        wp_cache_delete('currencies_all', 'premmerce_multicurrency');
    }

    /**
     *
     * Delete currency meta field. If $onlyForCurrency is not empty, works only for products with currency == $onlyForCurrency.
     * Otherwise, this field will be deleted for all products.
     *
     * @param $onlyForCurrency
     *
     *
     */
    public function deleteProductsCurrenciesMetaFields($onlyForCurrency = null)
    {
        $fieldsListProductVariation = array('_product_currency_regular_price', '_product_currency_sale_price');
        $fieldsListProduct = array_merge($fieldsListProductVariation, array('_product_currency'));

        $onlyForCurrency = apply_filters(
            'premmerce_multicurrency_delete_product_meta_fields_currency',
            $onlyForCurrency
        );

        $productsToUpdate = $this->getProductsIDs($onlyForCurrency);

        foreach ($productsToUpdate as $productToUpdateID) {
            $productObject = wc_get_product($productToUpdateID);
            if ($productObject->is_type('variable')) {
                $variationsToUpdateIDs = $productObject->get_children();
                foreach ($variationsToUpdateIDs as $variationID) {
                    $variationProduct = wc_get_product($variationID);
                    foreach ($fieldsListProductVariation as $field) {
                        $variationProduct->delete_meta_data($field);
                        $variationProduct->save();
                    }
                }
            }
            foreach ($fieldsListProduct as $fieldToDelete) {
                $productObject->delete_meta_data($fieldToDelete);
            }
        }

        do_action('premmerce_multicurrency_product_fields_deleted');
    }

    /**
     * @param $currencyId
     *
     * @return int|false $result
     *
     */
    public function deleteCurrency($currencyId)
    {
        $mainCurrencyId = self::getMainCurrencyId();

        //You can not delete main currency
        if ($currencyId == $mainCurrencyId) {
            return false;
        }

        $this->deleteProductsCurrenciesMetaFields($currencyId);


        //Check if this is the last available for users currency. If so, make main currency available.
        if ($this->isLastAvailableForUsersCurrency($currencyId)) {
            if ($this->getCurrencyById($currencyId)['display_on_front']) {
                $this->wpdb->update(
                    $this->currenciesTable,
                    array('display_on_front' => 1),
                    array('id' => $mainCurrencyId),
                    '%d',
                    '%d'
                );
            }
        }


        //Now we ready to delete currency
        $result = $this->wpdb->delete($this->currenciesTable, array('id' => $currencyId), '%d');

        $this->cleanCache();


        return $result;
    }

    /**
     * Drop currencies table
     */
    public function dropCurrenciesTable()
    {
        $this->wpdb->query("DROP TABLE IF EXISTS " . $this->currenciesTable);
    }
}
