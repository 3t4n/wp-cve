<?php namespace Premmerce\WoocommerceMulticurrency\Frontend;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\WoocommerceMulticurrency\Model\Model;

/**
 * Class Frontend
 *
 * @package Premmerce\WoocommerceMulticurrency\Frontend
 */
class Frontend
{
    /**
     * @var FileManager FileManager
     */
    private $fileManager;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var int
     */
    private $userCurrencyId;

    /**
     * Frontend constructor.
     *
     * @param FileManager $fileManager
     * @param Model $model
     * @param UserCurrencyHandler $userCurrencyHandler
     */
    public function __construct(FileManager $fileManager, Model $model, UserCurrencyHandler $userCurrencyHandler)
    {
        $this->fileManager = $fileManager;
        $this->model = $model;
        $this->userCurrencyId = $userCurrencyHandler->getUserCurrencyId();

        add_shortcode('multicurrency', array($this, 'registerMulticurrencyShortcode'));

        add_action('wp_enqueue_scripts', array($this, 'enqueueAssets'));

        if (isset($_GET['currency_id'])) {
            add_action('woocommerce_cart_loaded_from_session', array($this, 'updateCartTotals'));
        }
    }

    /**
     * Recalculate WC cart totals
     *
     * @param \WC_Cart $cart
     */
    public function updateCartTotals(\WC_Cart $cart)
    {
        $cart->calculate_totals();
    }

    /**
     * Scripts and styles
     */
    public function enqueueAssets()
    {
        wp_enqueue_script(
            'premmerce-multicurrency-selector',
            $this->fileManager->locateAsset('frontend/js/premmerce-multicurrency-selector.js'),
            array('jquery'),
            '',
            true
        );


        if (get_option('premmerce_multicurrency_ajax_prices_redraw')) {
            wp_enqueue_script(
                'premmerce-multicurrency-ajax-prices',
                $this->fileManager->locateAsset('frontend/js/premmerce-multicurrency-ajax-prices.js'),
                array('jquery-ui-slider', 'wc-jquery-ui-touchpunch', 'accounting'),
                '3.3.3'
            );

            wp_localize_script(
                'premmerce-multicurrency-ajax-prices',
                'premmerce_multicurrency_data',
                array(
                    'ajaxurl' => \WC_AJAX::get_endpoint('premmerce_get_prices'),
                )
            );
        }

        wp_enqueue_style(
            'multicurrency_widget_style',
            $this->fileManager->locateAsset('frontend/css/premmerce-multicurrency-selector.css')
        );
    }

    /**
     * Register shortcode for displaying currencies selector
     *
     *
     * @return string
     */
    public function registerMulticurrencyShortcode()
    {
        $addedCurrencies = $this->model->getCurrencies(true);
        return $this->fileManager->renderTemplate(
            'frontend/currency-selector.php',
            array('currencies' => $addedCurrencies, 'usersCurrency' => $this->userCurrencyId)
        );
    }
}
