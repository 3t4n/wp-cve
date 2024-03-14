<?php

namespace UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth;

trait NonceVerifier
{
    private function verify_nonce() : bool
    {
        return isset($_GET['security']) && \wp_verify_nonce(\wc_clean($_GET['security']), \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\OAuthField::NONCE_ACTION);
    }
}
