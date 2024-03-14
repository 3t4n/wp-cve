<?php

namespace UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth;

use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
class HookableObjects implements \UpsFreeVendor\WPDesk\PluginBuilder\Plugin\HookableCollection
{
    use HookableParent;
    /**
     * @var OAuthUrl
     */
    private $oauth_url;
    /**
     * @var TokenOption
     */
    private $token_option;
    /**
     * @var mixed
     */
    private $settings_url;
    /**
     * @var string
     */
    private $app;
    /**
     * @var string
     */
    private $test_api;
    public function __construct(\UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\OAuthUrl $oauth_url, \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption $token_option, $settings_url, string $app = 'live_rates', string $test_api = '')
    {
        $this->oauth_url = $oauth_url;
        $this->token_option = $token_option;
        $this->settings_url = $settings_url;
        $this->app = $app;
        $this->test_api = $test_api;
    }
    public function hooks()
    {
        $this->add_hookable(new \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Notices($this->token_option, $this->oauth_url->get_url(), $this->app));
        $this->add_hookable(new \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\CreateTokenAction($this->token_option, $this->oauth_url->get_url(), $this->app, $this->test_api));
        $this->add_hookable(new \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Ajax($this->token_option, $this->settings_url));
        $this->hooks_on_hookable_objects();
    }
}
