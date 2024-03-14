<?php

namespace UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth;

class OAuthField
{
    const NONCE_ACTION = 'ups_oauth';
    /**
     * @var string
     */
    private $key;
    /**
     * @var array
     */
    private $data;
    /**
     * @var string
     */
    private $field_key;
    /**
     * @var string
     */
    private $value;
    /**
     * @var TokenOption
     */
    private $token_option;
    /**
     * @var string
     */
    private $oauth_url;
    /**
     * @var string
     */
    private $app;
    /**
     * @var string
     */
    private $test_api;
    /**
     * @var string
     */
    private $settings_url;
    public function __construct(string $key, array $data, string $field_key, \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption $token_option, string $settings_url, string $oauth_url, string $app = 'live_rates', string $test_api = '')
    {
        $this->key = $key;
        $this->data = $data;
        $this->field_key = $field_key;
        $this->token_option = $token_option;
        $this->oauth_url = $oauth_url;
        $this->app = $app;
        $this->test_api = $test_api;
        $this->settings_url = $settings_url;
    }
    public function generate_oauth_html() : string
    {
        $nonce = \wp_create_nonce(self::NONCE_ACTION);
        $authorize_action = \sprintf('%s/action.php?action=start&return_url=%s&security=%s&ajax_url=%s&app=%s&test_api=%s', $this->oauth_url, \urlencode(\admin_url($this->settings_url . '&app=' . $this->app)), \urlencode($nonce), \urlencode(\admin_url('admin-ajax.php')), \urlencode($this->app), \urlencode($this->test_api));
        $revoke_action = \admin_url(\sprintf('admin-ajax.php?action=%s&security=%s', \urlencode(\UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Ajax::ajax_action_name($this->token_option)), \urlencode($nonce)));
        $field_key = $this->field_key;
        $value = $this->value;
        $field_class = $this->data['class'] ?? '';
        $token_option = $this->token_option;
        \ob_start();
        include __DIR__ . '/views/OAuth-field.php';
        return \ob_get_clean();
    }
}
