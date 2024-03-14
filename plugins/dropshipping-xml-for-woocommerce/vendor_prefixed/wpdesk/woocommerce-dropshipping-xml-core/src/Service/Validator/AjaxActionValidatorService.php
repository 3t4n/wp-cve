<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator;

use RuntimeException;
/**
 * Class AjaxActionValidatorService, validator for ajax action.
 * @package WPDesk\Library\DropshippingXmlCore\Service\Validator
 */
class AjaxActionValidatorService
{
    public function is_valid(string $security_code, string $nonce) : bool
    {
        if (!\wp_verify_nonce($security_code, $nonce)) {
            throw new \RuntimeException('Error, security code is not valid');
        }
        if (!\current_user_can('manage_options')) {
            throw new \RuntimeException('Error, you are not allowed to do this action');
        }
        return \true;
    }
}
