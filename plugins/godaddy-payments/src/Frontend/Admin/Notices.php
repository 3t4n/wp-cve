<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Frontend\Admin;

use Exception;
use GoDaddy\WooCommerce\Poynt\Helpers\ArrayHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\CommonHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\PoyntHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\WCHelper;
use GoDaddy\WooCommerce\Poynt\Plugin;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1\SV_WC_Admin_Notice_Handler;
use WC_Shipping_Zones;

/**
 * Class Notices.
 *
 * @since 1.3.0
 */
class Notices
{
    /** @var array sections to display GoDaddy Payment Recommendation */
    const GDP_RECOMMENDATION_SECTIONS = ['local_pickup_plus', 'cod', 'poynt_credit_card'];

    /** @var array sections to display GoDaddy Payment Recommendation for Sell in Person */
    const GDP_SIP_RECOMMENDATION_SECTIONS = ['local_pickup_plus', 'cod'];

    /** @var array tabs to display GoDaddy Payment Recommendation */
    const GDP_RECOMMENDATION_TABS = ['shipping'];

    /** @var array tabs to display GoDaddy Payment SIP Recommendation */
    const GDP_SIP_RECOMMENDATION_TABS = ['shipping'];

    /** @var string WC Local Pickup Shipping Method id */
    const WC_LOCAL_PICKUP = 'local_pickup';

    /** @var array registered admin notices */
    protected $notices = [];

    /**
     * Notices constructor.
     *
     * @since 1.3.0
     */
    public function __construct()
    {
        $this->registerHooks();
    }

    /**
     * Admin notice handler.
     *
     * @since 1.3.0
     *
     * @return SV_WC_Admin_Notice_Handler
     */
    protected function noticesHandler()
    {
        return poynt_for_woocommerce()->get_admin_notice_handler();
    }

    /**
     * Adds a notice for display.
     *
     * @since 1.3.0
     *
     * @param array $data
     */
    protected function registerNotice(array $data)
    {
        if (empty($data['id'])) {
            return;
        }

        $this->notices[$data['id']] = $data;
    }

    /**
     * Registers the notices that should be displayed.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @throws Exception
     */
    public function registerNotices()
    {
        $this->registerGdpRecommendationNotices();
        $this->registerGdpSipRecommendationNotices();
        $this->registerInvalidCountryCodeNotice();
    }

    /**
     * Register admin notices to display GoDaddy Payments Recommendation.
     *
     * @since 1.3.0
     *
     * @return void
     * @throws Exception
     */
    protected function registerGdpRecommendationNotices()
    {
        if (! $this->shouldRegisterGdpRecommendationNotices()) {
            return;
        }

        if (Plugin::CREDIT_CARD_GATEWAY_ID === ArrayHelper::get($_GET, 'section')) {
            $this->registerNotice([
                'dismissible' => false,
                'id'          => 'godaddy-payments-recommendation',
                'message'     => sprintf(
                    '<p>'.esc_html__('Set up GoDaddy Payments in minutes to securely accept credit and debit card transactions in your WooCommerce checkout.', 'godaddy-payments').'</p>
                    <a href="%2$s" class="button-primary woocommerce-save-button" target="_blank">'.esc_html__('Set up Godaddy Payments', 'godaddy-payments').'</a>',
                    esc_url(poynt_for_woocommerce()->get_plugin_url().'/assets/images/branding/gd-icon.svg'),
                    esc_url(poynt_for_woocommerce()->getSignupUrl())
                ),
                'notice_class' => 'notice-info',
            ]);
        } else {
            $this->registerNotice([
                'dismissible' => true,
                'id'          => 'godaddy-payments-recommendation',
                'message'     => sprintf(
                    '<img src="%1$s" alt="'.esc_attr('Provided by GoDaddy', 'godaddy-payments').'"/>
                    <h3>'.esc_html__('GoDaddy Payments', 'godaddy-payments').'</h3>
                    <p>'.esc_html__('Set up GoDaddy Payments in minutes to securely accept payments via Pay in Person terminal.', 'godaddy-payments').'</p>
                    <a href="%2$s" class="gdp-button" target="_blank">'.esc_html__('Set up Godaddy Payments', 'godaddy-payments').'</a>',
                    esc_url(poynt_for_woocommerce()->get_plugin_url().'/assets/images/branding/gd-icon.svg'),
                    esc_url(poynt_for_woocommerce()->getSignupUrl())
                ),
                'notice_class' => 'notice-info godaddy-payments-recommendation',
            ]);
        }
    }

    /**
     * Register admin notices to display GoDaddy Sell in Person Recommendation.
     *
     * @since 1.3.0
     *
     * @return void
     * @throws Exception
     */
    protected function registerGdpSipRecommendationNotices()
    {
        if (! $this->shouldRegisterGdpSipRecommendationNotices()) {
            return;
        }

        $this->registerNotice([
            'dismissible' => true,
            'id'          => 'godaddy-sip-recommendation',
            'message'     => sprintf(
                '<img src="%1$s" alt="'.esc_attr('Provided by GoDaddy', 'godaddy-payments').'"/>
                <h3>'.esc_html__('GoDaddy Payments', 'godaddy-payments').'</h3>
                <p>'.esc_html__('Set up GoDaddy Payments - Pay in Store to accept payments with POS terminal.', 'godaddy-payments').'</p>
                <a href="%2$s" class="gdp-button">'.esc_html__('Set up Godaddy Payments', 'godaddy-payments').'</a>',
                esc_url(poynt_for_woocommerce()->get_plugin_url().'/assets/images/branding/gd-icon.svg'),
                esc_url(poynt_for_woocommerce()->get_payment_gateway_configuration_url(Plugin::PAYINPERSON_GATEWAY_ID))
            ),
            'notice_class' => 'notice-info godaddy-payments-recommendation godaddy-sip-recommendation',
        ]);
    }

    /**
     * Register admin notices to display invalid currency code related notice.
     *
     * @since 1.3.0
     *
     * @return void
     * @throws Exception
     */
    protected function registerInvalidCountryCodeNotice()
    {
        if (! get_option('godaddy_payments_has_invalid_country_code')) {
            return;
        }

        $this->registerNotice([
            'dismissible' => true,
            'id'          => 'godaddy-invalid-currency-code',
            'message'     => sprintf(
                '<p>'.__('GoDaddy Payments has declined a transaction due to an invalid country code submitted in checkout. Please update your checkout to use ISO 3166-1 alpha-2 or alpha-3 standard country codes (example: US or USA) to prevent declines.', 'godaddy-payments').'</p>'
            ),
            'notice_class' => 'notice-error',
        ]);
    }

    /**
     * Determines whether GoDaddy Payments Recommendation Notice should be registered.
     *
     * @since 1.3.0
     *
     * @return bool
     * @throws Exception
     */
    protected function shouldRegisterGdpRecommendationNotices() : bool
    {
        if (PoyntHelper::isGDPConnected() || ! CommonHelper::isCurrentPage('wc-settings')) {
            return false;
        }

        return ArrayHelper::contains(static::GDP_RECOMMENDATION_SECTIONS, ArrayHelper::get($_GET, 'section'))
            || (ArrayHelper::contains(static::GDP_RECOMMENDATION_TABS, ArrayHelper::get($_GET, 'tab')) && ($this->islocalPickupEnabled() || $this->islocalDeliveryEnabled()));
    }

    /**
     * Determines whether GoDaddy Payments Recommendation Notice should be registered for Sell in Person.
     *
     * @since 1.3.0
     *
     * @return bool
     * @throws Exception
     */
    protected function shouldRegisterGdpSipRecommendationNotices() : bool
    {
        if (! PoyntHelper::isGDPConnected() || ! CommonHelper::isCurrentPage('wc-settings') || $this->isSiPGatewayEnabled()) {
            return false;
        }

        return
        ArrayHelper::contains(static::GDP_SIP_RECOMMENDATION_SECTIONS, ArrayHelper::get($_GET, 'section'))
        || (ArrayHelper::contains(static::GDP_SIP_RECOMMENDATION_TABS, ArrayHelper::get($_GET, 'tab')) && ($this->islocalPickupEnabled() || $this->islocalDeliveryEnabled()));
    }

    /**
     * Determines whether the zones have Local Pickup Method enabled.
     *
     * @since 1.3.0
     *
     * @return bool
     * @throws Exception
     */
    protected function islocalPickupEnabled() : bool
    {
        $shippingZones = WC_Shipping_Zones::get_zones();
        foreach (ArrayHelper::wrap($shippingZones) as $zone) {
            $localPickupShippingMethods = ArrayHelper::where(ArrayHelper::get($zone, 'shipping_methods', []), static function ($method) {
                return static::WC_LOCAL_PICKUP === $method->id;
            });

            return ! empty($localPickupShippingMethods);
        }

        return false;
    }

    /**
     * Determines whether the sell in person gateway have enabled.
     *
     * @since 1.3.0
     *
     * @return bool
     * @throws Exception
     */
    protected function isSiPGatewayEnabled() : bool
    {
        return WCHelper::isPaymentGatewayActive(Plugin::PAYINPERSON_GATEWAY_ID);
    }

    /**
     * Determines whether the zones have Local Delivery Method enabled.
     *
     * @since 1.3.0
     *
     * @return bool
     * @throws Exception
     */
    protected function islocalDeliveryEnabled() : bool
    {
        $shippingZones = WC_Shipping_Zones::get_zones();
        foreach (ArrayHelper::wrap($shippingZones) as $zone) {
            $localDeliveryShippingMethods = ArrayHelper::where(ArrayHelper::get($zone, 'shipping_methods', []), static function ($method) {
                return 'gdp_local_delivery' === $method->id;
            });

            return ! empty($localDeliveryShippingMethods);
        }

        return false;
    }

    /**
     * Registers the hooks.
     *
     * @since 1.3.0
     *
     * @return void
     */
    protected function registerHooks()
    {
        add_action('admin_init', [$this, 'registerNotices']);
        add_action('admin_notices', [$this, 'renderNotices']);
    }

    /**
     * Renders the notices.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @return void
     * @throws Exception
     */
    public function renderNotices()
    {
        if (! $user_id = get_current_user_id()) {
            return;
        }

        foreach ($this->notices as $data) {
            if (! $this->shouldRenderNotice($user_id, $data)) {
                continue;
            }

            $this->renderNotice($data);
        }
    }

    /**
     * Determines whether a notice should be rendered for the given user.
     *
     * @since 1.3.0
     *
     * @param int $user_id a user ID
     * @param array $data notice data
     * @return bool
     */
    public function shouldRenderNotice(int $user_id, array $data) : bool
    {
        return ! ArrayHelper::get($data, 'dismissible', true) || ! $this->noticesHandler()->is_notice_dismissed(ArrayHelper::get($data, 'id', ''), $user_id);
    }

    /**
     * Renders a notice.
     *
     * @since 1.3.0
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    protected function renderNotice(array $data)
    {
        if (empty($data['message'])) {
            return;
        }

        $this->noticesHandler()->add_admin_notice(
            ArrayHelper::get($data, 'message', ''),
            ArrayHelper::get($data, 'id', ''),
            [
                'notice_class' => ArrayHelper::get($data, 'notice_class', ''),
                'dismissible'  => ArrayHelper::get($data, 'dismissible', ''),
            ]
        );
    }
}
