<?php
namespace InspireLabs\WoocommerceInpost\shipping;

use WC_Checkout;
use WP_Error;


class Easypack_checkout_validator
{
    /**
     * @var WC_Checkout
     */
    private $wc_checkout;

    public function __construct()
    {
        add_action('woocommerce_after_checkout_validation', array($this, 'validate_phone_number_uk'), 10, 2);
        add_action('woocommerce_checkout_init', array($this, 'checkout_init'), 10);
        add_action('woocommerce_checkout_order_processed', array($this, 'uk_phone_trim_leading_zero'), 10, 1);
    }

    /**
     * @param int $order_id
     */
    public function uk_phone_trim_leading_zero($order_id)
    {
        $custom = get_post_custom($order_id);
        if (isset($custom['_billing_phone'][0])){
            if (strpos($custom['_billing_phone'][0], '0') === 0) {
                // It starts with zero
                update_post_meta(
                    $order_id,
                    '_billing_phone',
                    ltrim($custom['_billing_phone'][0], '0'));
            }
        }
    }

    /**
     * @param WC_Checkout $instance
     */
    public function checkout_init($instance)
    {
        $this->wc_checkout = $instance;
    }

    /**
     * @param array $data
     * @param WP_Error $errors
     */
    public function validate_phone_number_uk($data, $errors)
    {
        if (false == EasyPack_API()->is_uk()) {
            return;
        }

        $checkout = WC_Checkout::instance();

        foreach ($checkout->get_checkout_fields() as $fieldset_key => $fieldset) {
            if ($this->maybe_skip_fieldset($fieldset_key, $data)) {
                continue;
            }
            foreach ($fieldset as $key => $field) {
                if ( ! isset($data[$key])) {
                    continue;
                }
                $required    = ! empty($field['required']);
                $format      = array_filter(isset($field['validate']) ? (array)$field['validate'] : array());
                $field_label = isset($field['label']) ? $field['label'] : '';

                switch ($fieldset_key) {
                    case 'shipping' :
                        /* translators: %s: field name */
                        $field_label = sprintf(__('Shipping %s', 'woocommerce'), $field_label);
                        break;
                    case 'billing' :
                        /* translators: %s: field name */
                        $field_label = sprintf(__('Billing %s', 'woocommerce'), $field_label);
                        break;
                }

                if (in_array('phone', $format)) {

                    if (false === $this->is_uk_phone($data[$key])) {
                    $errors->add('validation', sprintf(__('Please enter a valid UK phone number.', 'woocommerce'),
                        '<strong>' . esc_html($field_label) . '</strong>'));
                    }
                }
            }
        }
    }

    /**
     * @param string $phone
     *
     * @return bool
     *
     */
    private function is_uk_phone($phone)
    {
        $regex = "/^([0-9]{10,11})$/";

        $cleaned_input = preg_replace('/\D/', '', (int)$phone); // leave only numbers
        if (preg_match($regex, $cleaned_input)) {
            return true;
        }

        return false;
    }


    /**
     * See if a fieldset should be skipped.
     *
     * @since 3.0.0
     *
     * @param string $fieldset_key
     * @param array $data
     *
     * @return bool
     */
    protected function maybe_skip_fieldset($fieldset_key, $data)
    {
        if ('shipping' === $fieldset_key && ( ! $data['ship_to_different_address'] || ! WC()->cart->needs_shipping_address())) {
            return true;
        }
        if ('account' === $fieldset_key && (is_user_logged_in() || ( ! $this->wc_checkout->is_registration_required() && empty($data['createaccount'])))) {
            return true;
        }
        return false;
    }
}

new Easypack_checkout_validator();