<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Support\Http\Adapters;

use GoDaddy\WooCommerce\Poynt\Helpers\WPNUXHelper;
use GoDaddy\WooCommerce\Poynt\Plugin;
use GoDaddy\WooCommerce\Poynt\Support;
use GoDaddy\WooCommerce\Poynt\Support\Http\Request;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1\SV_WC_Plugin_Exception;
use WP_Error;
use WP_User;

defined('ABSPATH') or exit;

/**
 * The support HTTP request adapter.
 *
 * @since 1.2.0
 */
class RequestAdapter
{
    /** @var array the source values */
    private $source;

    /**
     * RequestAdapter constructor.
     *
     * @since 1.2.0
     *
     * @param array $source
     */
    public function __construct(array $source)
    {
        $this->source = $source;
    }

    /**
     * Converts the source into a HTTP Request.
     *
     * @since 1.2.0
     *
     * @return Request
     * @throws SV_WC_Plugin_Exception
     */
    public function convertFromSource() : Request
    {
        $emailAddresses = isset($this->source['emailAddresses']) && (is_array($this->source['emailAddresses']) || is_string($this->source['emailAddresses'])) ? array_filter((array) $this->source['emailAddresses'], static function ($emailAddress) {
            return is_email($emailAddress);
        }) : null;

        if (empty($emailAddresses)) {
            throw new SV_WC_Plugin_Exception(__('At least one valid email address should be specified.', 'godaddy-payments'), 400);
        }

        return $this->newRequest()->setBody(json_encode([
            'data' => $this->convertData($this->source),
            'from' => implode(';', $emailAddresses),
        ]));
    }

    /**
     * Gets a new Request instance.
     *
     * @since 1.2.0
     *
     * @return Request
     */
    protected function newRequest() : Request
    {
        return new Request();
    }

    /**
     * Converts the source array into a data array.
     *
     * @since 1.2.0
     *
     * @param array $source
     * @return array
     * @throws SV_WC_Plugin_Exception
     */
    protected function convertData(array $source) : array
    {
        $supportUser = $this->getSupportUser();

        return [
            'customer' => $this->convertCustomer($source),
            'platform' => $this->getPlatform(),
            'plugin'   => [
                'name'             => 'GoDaddy Payments',
                'version'          => Plugin::VERSION,
                'support_end_date' => 'no subscription',
            ],
            'reason'               => $source['reason'] ?? '',
            'site_url'             => site_url(),
            'system_status_report' => $this->getSystemStatus(),
            'support_user'         => [
                'user_id'            => $supportUser ? (int) $this->getSupportUser()->ID : 0,
                'password_reset_url' => $supportUser ? $this->getSupportUserResetUrl($supportUser) : '',
            ],
            'ticket' => [
                'description' => $source['message'] ?? '',
                'subject'     => $source['subject'] ?? '',
            ],
        ];
    }

    /**
     * Converts the source’s emailAddresses value into an array of customer data.
     *
     * @since 1.2.0
     *
     * @param array $source
     * @return array
     */
    private function convertCustomer(array $source) : array
    {
        $user = null;

        if (! empty($source['emailAddresses'])) {
            foreach ((array) $source['emailAddresses'] as $email) {
                if ($user = get_user_by('email', $email)) {
                    break;
                }
            }
        }

        if (! $user instanceof WP_User) {
            $user = wp_get_current_user();
        }

        return [
            'name'  => $user->user_firstname && $user->user_lastname ? $user->user_firstname.' '.$user->user_lastname : $user->display_name,
            'email' => $user->user_email,
        ];
    }

    /**
     * Gets the support user.
     *
     * @since 1.2.0
     *
     * @return WP_User|null
     */
    private function getSupportUser()
    {
        return Support::getUser();
    }

    /**
     * Gets the password reset URL for the given user.
     *
     * @since 1.2.0
     *
     * @param WP_User $user
     * @return string
     * @throws SV_WC_Plugin_Exception
     */
    private function getSupportUserResetUrl(WP_User $user) : string
    {
        /** @var string|WP_Error $passwordResetKey */
        $passwordResetKey = get_password_reset_key(get_user_by('id', $user->ID));

        if (is_wp_error($passwordResetKey)) {
            throw new SV_WC_Plugin_Exception($passwordResetKey->get_error_message(), 404);
        }

        $parameters = http_build_query([
            'action' => 'rp',
            'key'    => $passwordResetKey,
            'login'  => rawurlencode($user->user_login),
        ], '', '&', PHP_QUERY_RFC3986);

        $url = network_site_url("wp-login.php?{$parameters}", 'login');

        // WordPress may filter this potentially to a non-string, so we ensure the type is the expected one
        if (! is_string($url) || ! wc_is_valid_url($url)) {
            throw new SV_WC_Plugin_Exception('The support user password reset URL is not a valid URL.', 500);
        }

        return $url;
    }

    /**
     * Gets the system status data.
     *
     * @since 1.2.0
     *
     * @return array
     */
    private function getSystemStatus() : array
    {
        return WC()->api->get_endpoint_data('/wc/v3/system_status');
    }

    /**
     * Gets the appropriate platform value according to the current WPNUX product.
     *
     * @since 1.2.0
     *
     * @return string
     */
    private function getPlatform() : string
    {
        if (WPNUXHelper::isMWPSite()) {
            return 'mwp';
        }

        if (WPNUXHelper::isBHSite()) {
            return 'bh';
        }

        if (WPNUXHelper::isCPanelSite()) {
            return 'cpanel';
        }

        return '';
    }
}
