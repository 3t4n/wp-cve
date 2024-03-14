<?php

namespace UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Actions;

use UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Exceptions\RefreshTokenException;
use UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption;
class RefreshToken
{
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
     * @var bool
     */
    private $is_testing;
    public function __construct(\UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption $token_option, string $oauth_url, string $app = 'live_rates', bool $is_testing = \false)
    {
        $this->token_option = $token_option;
        $this->oauth_url = $oauth_url;
        $this->app = $app;
        $this->is_testing = $is_testing;
    }
    public function handle()
    {
        $response = $this->request_token_refresh();
        if (\is_wp_error($response)) {
            throw new \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Exceptions\RefreshTokenException($response->get_error_message());
        } else {
            $response_body = \json_decode(\wp_remote_retrieve_body($response), \true);
            if (isset($response_body['status'])) {
                $status = $response_body['status'];
                if ($status === 'error') {
                    throw new \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Exceptions\RefreshTokenException($response_body['message']);
                } else {
                    $token = $response_body['token'];
                    $this->token_option->set($token);
                    $this->token_option->update_issued_at_to_current_time();
                }
            } else {
                throw new \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Exceptions\RefreshTokenException(\__('Error during refresh.', 'flexible-shipping-ups'));
            }
        }
    }
    /**
     * @return mixed
     */
    public function request_token_refresh()
    {
        return \wp_remote_post(\sprintf('%s/refresh-token.php?app=%s&test_api=%s', $this->oauth_url, $this->app, $this->is_testing ? '1' : ''), ['body' => ['refresh_token' => $this->token_option->get_refresh_token()]]);
    }
}
