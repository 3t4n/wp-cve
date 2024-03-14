<?php

namespace UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth;

use UpsFreeVendor\Octolize\Ups\RestApi\RestApiToken as UpsRestApiToken;
use UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Actions\RefreshToken;
class RestApiToken implements \UpsFreeVendor\Octolize\Ups\RestApi\RestApiToken
{
    /**
     * @var TokenOption
     */
    private $token_option;
    /**
     * @var RefreshToken
     */
    private $refresh_token_action;
    public function __construct(\UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption $token_option, \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\Actions\RefreshToken $refresh_token_action)
    {
        $this->token_option = $token_option;
        $this->refresh_token_action = $refresh_token_action;
    }
    public function get_token() : string
    {
        if (empty($this->token_option->get_access_token())) {
            return '';
        }
        if ($this->token_option->is_token_expired()) {
            try {
                $this->refresh_token_action->handle();
            } catch (\Exception $e) {
                return '';
            }
        }
        return $this->token_option->get_access_token();
    }
}
