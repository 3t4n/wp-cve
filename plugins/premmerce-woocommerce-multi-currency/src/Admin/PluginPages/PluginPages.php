<?php

namespace Premmerce\WoocommerceMulticurrency\Admin\PluginPages;

use  Premmerce\SDK\V2\FileManager\FileManager ;
use  Premmerce\WoocommerceMulticurrency\Admin\Admin ;
use  Premmerce\WoocommerceMulticurrency\Admin\CurrenciesListTable ;
use  Premmerce\WoocommerceMulticurrency\Admin\RatesUpdate\RatesUpdateController ;
use  Premmerce\WoocommerceMulticurrency\Admin\UpdatersListTable ;
use  Premmerce\WoocommerceMulticurrency\Model\Model ;
/**
 * Class PluginPages
 *
 * This class responsible for all admin pages added by this plugin.
 */
class PluginPages
{
    /**
     * @var string
     */
    private  $pageSlug = 'premmerce_multicurrency' ;
    /**
     * @var FileManager
     */
    private  $fileManager ;
    /**
     * @var Model;
     */
    private  $model ;
    /**
     * @var RatesUpdateController
     */
    private  $ratesUpdateController ;
    /**
     * PluginPages constructor.
     *
     * @param $fileManager
     * @param $model
     * @param $ratesUpdateController
     */
    public function __construct( $fileManager, $model, $ratesUpdateController )
    {
        $this->fileManager = $fileManager;
        $this->model = $model;
        $this->ratesUpdateController = $ratesUpdateController;
    }
    
    /**
     * Register Premmerce Multicurrency admin menu item
     */
    public function addMenuItem()
    {
        global  $admin_page_hooks ;
        $premmerceMenuExists = isset( $admin_page_hooks['premmerce'] );
        $svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20" height="16" style="fill:#82878c" viewBox="0 0 20 16"><g id="Rectangle_7"> <path d="M17.8,4l-0.5,1C15.8,7.3,14.4,8,14,8c0,0,0,0,0,0H8h0V4.3C8,4.1,8.1,4,8.3,4H17.8 M4,0H1C0.4,0,0,0.4,0,1c0,0.6,0.4,1,1,1 h1.7C2.9,2,3,2.1,3,2.3V12c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1V1C5,0.4,4.6,0,4,0L4,0z M18,2H7.3C6.6,2,6,2.6,6,3.3V12 c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1v-1.7C8,10.1,8.1,10,8.3,10H14c1.1,0,3.2-1.1,5-4l0.7-1.4C20,4,20,3.2,19.5,2.6 C19.1,2.2,18.6,2,18,2L18,2z M14,11h-4c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1h4c0.6,0,1-0.4,1-1C15,11.4,14.6,11,14,11L14,11z M14,14 c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1C15,14.4,14.6,14,14,14L14,14z M4,14c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1 c0.6,0,1-0.4,1-1C5,14.4,4.6,14,4,14L4,14z"/></g></svg>';
        $svg = 'data:image/svg+xml;base64,' . base64_encode( $svg );
        if ( !$premmerceMenuExists ) {
            add_menu_page(
                'Premmerce',
                'Premmerce',
                'manage_options',
                'premmerce',
                '',
                $svg
            );
        }
        add_submenu_page(
            'premmerce',
            __( 'Premmerce Multi-Currency', 'premmerce-woocommerce-multicurrency' ),
            __( 'Multi-Currency', 'premmerce-woocommerce-multicurrency' ),
            'manage_options',
            Admin::PAGE_SLUG,
            array( $this, 'renderPluginPage' )
        );
        
        if ( !$premmerceMenuExists ) {
            global  $submenu ;
            unset( $submenu['premmerce'][0] );
        }
    
    }
    
    /**
     * Premmerce Multi-currency page callback
     */
    public function renderPluginPage()
    {
        $action = ( isset( $_GET['action'] ) ? $_GET['action'] : '' );
        $mainCurrency = $this->model->getMainCurrency();
        $availableUpdaters = $this->ratesUpdateController->getAvailableUpdaters();
        $updaters = array();
        foreach ( $availableUpdaters as $updater ) {
            $updaterId = $updater->getId();
            $updaters[$updaterId] = $updater->getPublicName();
        }
        switch ( $action ) {
            case 'edit-currency':
                $this->renderCurrencyEditPage( $updaters, $mainCurrency );
                break;
            case 'edit-updater':
                $this->renderEditUpdaterPage();
                break;
            default:
                $this->renderCurrenciesTablePage( $updaters, $mainCurrency );
        }
    }
    
    /**
     * Add settings page content
     */
    public function registerSettings()
    {
        register_setting( 'multicurrency_settings', 'premmerce_multicurrency_ajax_prices_redraw', array(
            'type'              => 'boolean',
            'sanitize_callback' => 'boolval',
        ) );
        add_settings_section(
            'multicurrency_caching_settings',
            __( 'Cache', 'premmerce-woocommerce-multicurrency' ),
            '',
            Admin::PAGE_SLUG
        );
        add_settings_field(
            'prices_ajax_redraw',
            '',
            function () {
            $val = get_option( 'premmerce_multicurrency_ajax_prices_redraw', false );
            $this->fileManager->includeTemplate( 'admin/ajax-prices-redraw-checkbox.php', array(
                'val' => $val,
            ) );
        },
            Admin::PAGE_SLUG,
            'multicurrency_caching_settings'
        );
        $frequencyInputTemplate = 'admin/rates-updater-schedule-frequency-select.php';
    }
    
    /**
     * Move Woocommerce currency options to plugin settings
     *
     * @param $settings
     *
     * @return mixed
     */
    public function moveWoocommerceOptions( $settings )
    {
        $disabledList = array(
            'woocommerce_currency',
            'woocommerce_currency_pos',
            'woocommerce_price_thousand_sep',
            'woocommerce_price_decimal_sep',
            'woocommerce_price_num_decimals'
        );
        $pluginPageLink = '<a href="' . esc_url( admin_url( 'admin.php' ) . '?page=' . Admin::PAGE_SLUG ) . '">' . __( 'this', 'premmerce-woocommerce-multicurrency' ) . '</a>';
        foreach ( $settings as $index => $settingArray ) {
            
            if ( in_array( $settingArray['id'], $disabledList ) ) {
                unset( $settings[$index] );
            } elseif ( 'pricing_options' === $settingArray['id'] ) {
                $settings[$index]['desc'] = sprintf( __( 'Currency options was moved to %s page by Premmerce Multicurrency plugin.', 'premmerce-woocommerce-multicurrency' ), $pluginPageLink );
            }
        
        }
        return $settings;
    }
    
    /**
     * Add css and js on admin page
     *
     */
    public function enqueueAdminAssets()
    {
        
        if ( stristr( get_current_screen()->id, 'premmerce_multicurrency' ) ) {
            wp_enqueue_script( 'jquery-ui-progressbar' );
            //jQuery validate
            wp_enqueue_script( 'validate-plugin', '//cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js', array( 'jquery' ) );
            $lang = ( 'uk' === get_locale() ? 'uk' : substr( get_locale(), 0, strpos( get_locale(), '_' ) ) );
            if ( $lang != 'en' ) {
                //try to load validate plugin translation
                wp_enqueue_script( 'validate-localization-' . $lang, '//cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/localization/messages_' . $lang . '.js', array( 'validate-plugin' ) );
            }
            wp_localize_script( 'validate-plugin', 'premmerceMulticurrencyData', array(
                'language' => $lang,
            ) );
            //Select2
            wp_enqueue_script(
                'select2',
                'https://cdn.jsdelivr.net/npm/select2@4.0.6-rc.1/dist/js/select2.min.js',
                array( 'jquery' ),
                false,
                true
            );
            wp_enqueue_style( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.0.5/dist/css/select2.min.css' );
            //Font awesome
            wp_enqueue_style( 'font-awesome', 'https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css' );
            //Own styles and scripts
            wp_enqueue_script(
                'premmerce_multicurrency_script',
                $this->fileManager->locateAsset( 'admin/js/premmerce-multicurrency.js' ),
                array( 'jquery' ),
                '',
                true
            );
            wp_enqueue_style( 'premmerce_multicurrency_admin_style', $this->fileManager->locateAsset( 'admin/css/premmerce-multicurrency.css' ) );
            wp_enqueue_style(
                'e2b-admin-ui-css',
                'https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',
                false,
                "1.9.0",
                false
            );
            if ( isset( $_GET['currency_id'] ) && ($currency = $this->model->getCurrencyById( intval( $_GET['currency_id'] ) )) ) {
                $currencies['editedCurrencyCode'] = $currency['code'];
            }
            $currencies['existCurrencies'] = array_keys( $this->model->getCurrencies() );
            $currencies['shopCurrency'] = get_option( 'woocommerce_currency' );
            $currencies['nonces']['changeShopCurrency'] = wp_create_nonce( Admin::CHANGE_SHOP_CURRENCY_ACTION );
            $currencies['nonces']['recalculateProductsPrices'] = wp_create_nonce( Admin::RECALCULATE_PRICES_ACTION );
            $currencies['nonces']['getUpdaters'] = wp_create_nonce( Admin::GET_UPDATERS_ACTION );
            $currencies['nonces']['getCurrencyRate'] = wp_create_nonce( Admin::GET_CURRENCY_RATE_ACTION );
            $currencies['nonces'][Admin::CHECK_UPDATERS_STATUSES_ACTION] = wp_create_nonce( Admin::CHECK_UPDATERS_STATUSES_ACTION );
            $currencies['nonces']['dismissUpdaterMessage'] = wp_create_nonce( Admin::DISMISS_UPDATER_MESSAGE_ACTION );
            $currencies['messages']['deleteMessage'] = __( 'You are about to delete currency. All products prices in this currency will be recalculated. This action can\'t be undone. Are you sure?', 'premmerce-woocommerce-multicurrency' );
            $currencies['messages']['changeMainCurrencyMessage'] = __( 'You are about to change main currency. All products prices and other currencies rates will be recalculated. Are you sure?', 'premmerce-woocommerce-multicurrency' );
            $currencies['messages']['changeMainCurrencyMessageFree'] = __( 'You are about to change main currency. Are you sure?', 'premmerce-woocommerce-multicurrency' );
            $currencies['messages']['progress'] = __( 'Progress:', 'premmerce-woocommerce-multicurrency' );
            $currencies['messages']['done'] = __( 'Done!', 'premmerce-woocommerce-multicurrency' );
            $currencies['messages']['rateMoreThanZero'] = __( 'Currency rate must be more than zero.', 'premmerce-woocommerce-multicurrency' );
            $currencies['messages']['noCurrencyInUpdater'] = __( 'This currency not supported by selected service. Please, select another one.', 'premmerce-woocommerce-multicurrency' );
            $currencies['messages']['noUpdaterSelected'] = __( 'Please, select service, you want to get rates from.', 'premmerce-woocommerce-multicurrency' );
            $currencies['messages']['updaterAvailable'] = __( 'Available', 'premmerce-woocommerce-multicurrency' );
            $currencies['messages']['updaterUnavailable'] = __( 'Unavailable', 'premmerce-woocommerce-multicurrency' );
            $countriesWithCurrencies = $this->model->getCountriesCurrenciesCodes();
            foreach ( $countriesWithCurrencies as $country => $currency ) {
                $currencies['countriesAndCurrenciesCodes'][$currency][] = $country;
            }
            $currencies['isPremium'] = false;
            wp_localize_script( 'premmerce_multicurrency_script', 'currencies', $currencies );
        }
    
    }
    
    /**
     * Sanitizes rates updater frequency option and schedules WP Cron event.
     *
     *
     * @param $timeInSeconds
     *
     * @return int
     */
    public function ratesUpdaterCallback( $timeInSeconds )
    {
        current_user_can( 'manage_woocommerce' ) || die;
        $nextScheduled = wp_next_scheduled( Model::RATES_UPDATER_CRON_HOOK );
        
        if ( !$timeInSeconds ) {
            //Auto update disabled
            wp_unschedule_event( $nextScheduled, Model::RATES_UPDATER_CRON_HOOK );
            return 0;
        }
        
        if ( $this->model->getRatesUpdaterCronScheduleTime() === $timeInSeconds ) {
            //Auto update schedule not changed
            return $timeInSeconds;
        }
        //Schedule event only if schedule doesn't exist or frequency changed.
        
        if ( $timeInSeconds !== get_option( MODEL::RATES_UPDATER_INTERVAL_OPTION_NAME ) || !$nextScheduled ) {
            if ( $nextScheduled ) {
                wp_unschedule_event( $nextScheduled, Model::RATES_UPDATER_CRON_HOOK );
            }
            $this->model->addCronSchedule( $timeInSeconds );
            wp_schedule_event( time() + $timeInSeconds, MODEL::RATES_UPDATER_INTERVAL_NAME, Model::RATES_UPDATER_CRON_HOOK );
        }
        
        $message = __( 'Settings was successfully saved.', 'premmerce-woocommerce-multicurrency' );
        add_settings_error(
            'multicurrency_caching_settings',
            '',
            esc_html( $message ),
            'updated'
        );
        return $timeInSeconds;
    }
    
    /**
     * Edit currency page
     *
     * @param $updatersList
     * @param $mainCurrencyArray
     */
    private function renderCurrencyEditPage( $updatersList, $mainCurrencyArray )
    {
        $currencyID = (int) $_GET['currency_id'];
        $currencyData = $this->model->getCurrencyById( $currencyID );
        $currencyData['countries'] = ( isset( get_option( Model::DEFAULT_COUNTRY_CURRENCY_OPTION_NAME )[$currencyID] ) ? get_option( Model::DEFAULT_COUNTRY_CURRENCY_OPTION_NAME )[$currencyID]['countries'] : array() );
        $currencyData['countries_type'] = ( isset( get_option( Model::DEFAULT_COUNTRY_CURRENCY_OPTION_NAME )[$currencyID] ) ? get_option( Model::DEFAULT_COUNTRY_CURRENCY_OPTION_NAME )[$currencyID]['type'] : 'include' );
        $formFields = $this->fileManager->renderTemplate( 'admin/currency-form-fields.php', array(
            'page'             => 'edit-currency',
            'currencyData'     => $currencyData,
            'updaters'         => $updatersList,
            'mainCurrencyId'   => $this->model->getMainCurrency()['id'],
            'mainCurrencyCode' => $mainCurrencyArray['code'],
            'canBeUpdatedWith' => $this->ratesUpdateController->getUpdatersForCurrency( $currencyData['code'] ),
        ) );
        $this->fileManager->includeTemplate( 'admin/edit-currency-page.php', array(
            'formFields'   => $formFields,
            'currencyData' => $currencyData,
            'mainCurrency' => (int) $mainCurrencyArray['id'],
        ) );
    }
    
    /**
     * Edit updater admin page
     */
    private function renderEditUpdaterPage()
    {
        $updaterId = sanitize_text_field( wp_unslash( $_GET['updater'] ) );
        $updaterInstance = $this->ratesUpdateController->getUpdaterById( $updaterId );
        $this->fileManager->includeTemplate( 'admin/rates-updater-settings-page.php', array(
            'updaterPublicName'     => $updaterInstance->getPublicName(),
            'updaterSettingsFields' => $updaterInstance->getAdminOptionsFields(),
            'updaterId'             => $updaterInstance->getId(),
        ) );
    }
    
    /**
     * Render currencies table page (default plugin page tab)
     *
     * @param $updaters
     * @param $mainCurrencyData
     */
    private function renderCurrenciesTablePage( $updaters, $mainCurrencyData )
    {
        if ( !class_exists( 'WP_List_Table' ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
        }
        //Todo: do not instantiate both classes
        $currenciesTable = new CurrenciesListTable( $this->model );
        $updatersTable = new UpdatersListTable( $this->ratesUpdateController );
        $formFields = $this->fileManager->renderTemplate( 'admin/currency-form-fields.php', array(
            'page'             => 'add-currency',
            'updaters'         => $updaters,
            'mainCurrencyCode' => $mainCurrencyData['code'],
        ) );
        $this->fileManager->includeTemplate( 'admin/currencies-page/currencies-page.php', array(
            'formFields'      => $formFields,
            'currenciesTable' => $currenciesTable,
            'updatersTable'   => $updatersTable,
            'pageSlug'        => $this->pageSlug,
        ) );
    }

}