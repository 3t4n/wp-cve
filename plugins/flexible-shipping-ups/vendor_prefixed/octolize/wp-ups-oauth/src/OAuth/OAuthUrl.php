<?php

namespace UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth;

class OAuthUrl
{
    public function get_url()
    {
        return \apply_filters('flexible-shipping-ups-oauth-url', 'https://ups-oauth.octolize.com');
    }
}
