<?php

namespace Premmerce\WoocommerceMulticurrency\Frontend;

use  Premmerce\SDK\V2\FileManager\FileManager ;
use  Premmerce\WoocommerceMulticurrency\Model\Model ;
//todo: rename this class to UserCurrencyManager()
class UserCurrencyHandler
{
    /**
     * @var Model
     */
    private  $model ;
    /**
     * @var FileManager
     */
    private  $fileManager ;
    /**
     * @var string
     */
    private  $userCurrencyId ;
    /**
     * @var array
     */
    private  $userCurrencyData ;
    /**
     * UserCurrencyHandler constructor.
     *
     * @param Model $model
     * @param FileManager $fileManager
     */
    public function __construct( Model $model, FileManager $fileManager )
    {
        $this->model = $model;
        $this->fileManager = $fileManager;
        $currencyFromGet = $this->getCurrencyIdByGET();
        if ( $currencyFromGet && $this->currencyAvailableForUser( $currencyFromGet ) ) {
            $this->setCurrencyCookie( $currencyFromGet );
        }
        $this->userCurrencyId = $this->detectUserCurrencyId();
        $this->userCurrencyData = $this->model->getCurrencies( true )[$this->userCurrencyId];
        //todo: set this filters on frontend only
        $this->setFilters();
    }
    
    /**
     * Get user currency Id
     *
     * @return string
     */
    public function getUserCurrencyId()
    {
        return apply_filters( 'premmerce_multicurrency_get_users_currency_id', $this->userCurrencyId );
    }
    
    /**
     * Get user currency data array
     *
     * @return array
     */
    public function getUserCurrencyData()
    {
        return apply_filters( 'premmerce_multicurrency_get_users_currency_data', $this->userCurrencyData );
    }
    
    /**
     * Return value of given field from user currency data
     *
     * @param $field
     *
     * @return string
     */
    public function getUserCurrencyField( $field )
    {
        $value = ( isset( $this->getUserCurrencyData()[$field] ) ? $this->getUserCurrencyData()[$field] : '' );
        return apply_filters(
            'premmerce_multicurrency_get_users_currency_field',
            $value,
            $field,
            $this->userCurrencyId
        );
    }
    
    /**
     * Set filters related to user currency
     */
    public function setFilters()
    {
        add_filter( 'woocommerce_currency', function () {
            return $this->getUserCurrencyField( 'code' );
        } );
        //Filter Woocommerce currency format options
        $optionsToReplace = array(
            'price_thousand_separator' => 'thousand_separator',
            'price_decimal_separator'  => 'decimal_separator',
            'price_decimals'           => 'decimals_num',
        );
        foreach ( $optionsToReplace as $optionName => $optionValue ) {
            add_filter( 'wc_get_' . $optionName, function ( $originalOptionValue ) use( $optionValue ) {
                $newValue = $originalOptionValue;
                if ( isset( $this->getUserCurrencyData()[$optionValue] ) ) {
                    $newValue = $this->getUserCurrencyData()[$optionValue];
                }
                return $newValue;
            } );
        }
        add_filter( 'option_woocommerce_currency_pos', function ( $orig ) {
            return ( $this->getUserCurrencyData()['position'] ?: $orig );
        } );
        add_filter( 'woocommerce_currency_symbol', function ( $originalSymbol ) {
            return ( $this->getUserCurrencyData()['symbol'] ?: $originalSymbol );
        } );
    }
    
    /**
     * Check if currency available for users on frontend
     *
     * @param $currencyID
     *
     * @return bool
     *
     *
     */
    public function currencyAvailableForUser( $currencyID )
    {
        return array_key_exists( $currencyID, $this->model->getCurrencies( true ) );
    }
    
    /**
     * Detect user currency id
     *
     * @return string
     */
    private function detectUserCurrencyId()
    {
        $ids = array(
            //priority depends on order
            $this->getCurrencyIdByGET(),
            $this->getCurrencyIdByCookie(),
            $this->getCurrencyIdByCountry(),
            $this->model->getMainCurrencyId(),
            (string) key( $this->model->getCurrencies( true ) ),
        );
        $currency = current( array_filter( $ids, array( $this, 'currencyAvailableForUser' ) ) );
        return apply_filters( 'premmerce_multicurrency_detect_users_currency_id', $currency, $this->model->getCurrencies( true ) );
    }
    
    /**
     * Get currency id from cookie
     *
     * @return string
     */
    private function getCurrencyIdByCookie()
    {
        return filter_input( INPUT_COOKIE, 'premmerce_users_currency', FILTER_SANITIZE_NUMBER_INT );
    }
    
    /**
     * Get currency id based on user country
     *
     * @return string
     */
    private function getCurrencyIdByCountry()
    {
    }
    
    /**
     * Get sanitized currency id from GET if set, 0 otherwise.
     *
     * @return string
     */
    private function getCurrencyIdByGET()
    {
        $currency = ( isset( $_GET['currency_id'] ) ? filter_input( INPUT_GET, 'currency_id', FILTER_SANITIZE_NUMBER_INT ) : '' );
        return $currency;
    }
    
    /**
     * Set user currency id to cookie
     *
     * @param int $currency
     */
    private function setCurrencyCookie( $currency )
    {
        setcookie(
            'premmerce_users_currency',
            $currency,
            time() + MONTH_IN_SECONDS,
            '/'
        );
    }

}