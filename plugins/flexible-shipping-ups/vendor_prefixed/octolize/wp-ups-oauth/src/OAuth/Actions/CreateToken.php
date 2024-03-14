<?php

namespace UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Actions;

use UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Exceptions\CreateTokenException;
use UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption;
class CreateToken
{
    /**
     * @var string
     */
    private $code;
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
    public function __construct(string $code, \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption $token_option, string $oauth_url, string $app = 'live_rates', string $test_api = '')
    {
        $this->code = $code;
        $this->token_option = $token_option;
        $this->oauth_url = $oauth_url;
        $this->app = $app;
        $this->test_api = $test_api;
    }
    public function handle()
    {
        $response = $this->request_token_create();
        if (\is_wp_error($response)) {
            throw new \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Exceptions\CreateTokenException($response->get_error_message());
        } else {
            $response_body = \json_decode(\wp_remote_retrieve_body($response), \true);
            if (isset($response_body['status'])) {
                $status = $response_body['status'];
                if ($status === 'error') {
                    throw new \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Exceptions\CreateTokenException($response_body['message']);
                } else {
                    $token = $response_body['token'];
                    $this->token_option->set($token);
                    $this->token_option->update_issued_at_to_current_time();
                }
            } else {
                throw new \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Exceptions\CreateTokenException(\__('Error during authorization.', 'flexible-shipping-ups'));
            }
        }
    }
    /**
     * @return mixed
     */
    public function request_token_create()
    {
        return \wp_remote_post(\sprintf('%s/create-token.php', $this->oauth_url), ['body' => ['code' => $this->code, 'app' => $this->app, 'test_api' => $this->test_api]]);
    }
}
