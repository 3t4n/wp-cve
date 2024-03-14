<?php

namespace UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth;

use UpsFreeVendor\WPDesk\Notice\Notice;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class Notices implements \UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    use NonceVerifier;
    const PRIORITY_BEFORE_DEFAULT = 9;
    /**
     * @var TokenOption
     */
    private $token_option;
    /**
     * @var string
     */
    private $oauth_url;
    /**
     * @var mixed|string
     */
    private $app;
    public function __construct(\UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption $token_option, string $oauth_url, $app = 'live_rates')
    {
        $this->token_option = $token_option;
        $this->oauth_url = $oauth_url;
        $this->app = $app;
    }
    public function hooks()
    {
        \add_action('admin_notices', [$this, 'oauth_notice'], self::PRIORITY_BEFORE_DEFAULT);
    }
    public function oauth_notice()
    {
        if (isset($_GET['app']) && $_GET['app'] === $this->app && isset($_GET['ups-oauth-status']) && isset($_GET['message']) && $this->verify_nonce()) {
            $status = \wc_clean($_GET['ups-oauth-status']);
            $message = \wc_clean($_GET['message']);
            if ($status === 'success') {
                new \UpsFreeVendor\WPDesk\Notice\Notice($message, \UpsFreeVendor\WPDesk\Notice\Notice::NOTICE_TYPE_SUCCESS);
            }
            if ($status === 'error') {
                new \UpsFreeVendor\WPDesk\Notice\Notice($message, \UpsFreeVendor\WPDesk\Notice\Notice::NOTICE_TYPE_ERROR);
            }
        }
    }
}
