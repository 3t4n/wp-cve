<?php

namespace UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth;

use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class Ajax implements \UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const AJAX_ACTION_REVOKE = 'ups_revoke';
    /**
     * @var TokenOption
     */
    private $token_option;
    /**
     * @var string
     */
    private $settings_url;
    public function __construct(\UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption $token_option, string $settings_url)
    {
        $this->token_option = $token_option;
        $this->settings_url = $settings_url;
    }
    public static function ajax_action_name(\UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption $token_option) : string
    {
        return 'wpdesk_ajax_' . self::AJAX_ACTION_REVOKE . '_' . $token_option->get_option_name();
    }
    public function hooks()
    {
        \add_action('wp_ajax_' . self::ajax_action_name($this->token_option), [$this, 'delete_oauth_data']);
    }
    public function delete_oauth_data()
    {
        if (\wp_verify_nonce($_REQUEST['security'], \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\OAuthField::NONCE_ACTION)) {
            $this->token_option->set([]);
            $status = 'success';
            $message = \__('Successfully revoked UPS Authorization.', 'flexible-shipping-ups');
            $security = \wp_create_nonce(\UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\OAuthField::NONCE_ACTION);
        } else {
            $status = 'error';
            $message = \__('Error during revoke authorization.', 'flexible-shipping-ups');
            $security = \wp_create_nonce(\UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\OAuthField::NONCE_ACTION);
        }
        \wp_safe_redirect(\admin_url($this->settings_url . '&ups-oauth-status=' . $status . '&message=' . \urlencode($message) . '&security=' . \urlencode($security)));
        die;
    }
}
