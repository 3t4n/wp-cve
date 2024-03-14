<?php

namespace PlatiOnlinePO6\Inc\Core;

/**
 * @link              https://plati.online
 * @since             6.0.0
 * @package           PlatiOnlinePO6
 *
 */

use PlatiOnlinePO6\Inc\Core\WC_PlatiOnline as WC_PlatiOnline;
use PlatiOnlinePO6\Inc\Libraries\phpseclib\Crypt\RSA as RSA;
use PlatiOnlinePO6\Inc\Libraries\PO5 as PO5;
use PlatiOnlinePO6\Inc\Libraries\sylouuu\Curl\Method as Curl;

class WC_PlatiOnline_Login
{
    public static $can_activate = false;
    private $plugin_name;
    private $version;
    private $plugin_text_domain;

    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_text_domain = $plugin_text_domain;
    }

    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . '../front/css/plationline-login.css', array(), $this->version, 'all');
        self::$can_activate = $this->can_activate();
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . '../front/js/plationline-login.js', array('jquery'), $this->version, true);
    }

    public function can_activate()
    {
        $po5_settings = (new WC_PlatiOnline())->settings;

        if (!empty($po5_settings['merchant_id_' . \strtolower(\get_woocommerce_currency())]) && !empty($po5_settings['enabled_login']) && $po5_settings['enabled_login'] === 'yes' && !empty($po5_settings['rsa_login'])) {
            return true;
        }
        return false;
    }

    public function print_button()
    {
        if (self::$can_activate) {
            $po5_settings = (new WC_PlatiOnline())->settings;
            $po = new PO5();
            $data = array(
                'f_action' => '030',
                'f_login' => $po5_settings['merchant_id_' . \strtolower(\get_woocommerce_currency())],
                'response_type' => 'code',
                'f_lang' => \substr(get_bloginfo('language'), 0, 2),
                'state' => ($po5_settings['test_mode_login'] === 'DEMO' ? 'test_account' : 'live_account'),
                'redirect_uri' => get_bloginfo('url') . '/wc-api/wc_login_plationline',
                'scope' => \urlencode('accountInfo,billingAddress,shippingAddress'),
                'singleAddress' => 'true', //customer can select only 1 billing address and 1 shipping address
            );
            $url = add_query_arg($data, $po::$url_login_plationline);
            echo '<div id="po-login-container">
                    <div id="po-login-or"><span>' . __('or', 'plationline') . '</span></div>
                    <a id="po6-login" href="' . $url . '">
                        <img id="po6-logo" src="' . plugin_dir_url(__FILE__) . '../../assets/images/logo-wallet.png">
                        <span>' . __('Login with Plati.Online Account', 'plationline') . '</span>
                    </a>
                  </div>';
        }
    }

    public function import_button()
    {
        if (self::$can_activate) {
            $po5_settings = (new WC_PlatiOnline())->settings;
            $po = new PO5();
            $data = array(
                'f_action' => '030',
                'f_login' => $po5_settings['merchant_id_' . \strtolower(\get_woocommerce_currency())],
                'response_type' => 'code',
                'f_lang' => \substr(get_bloginfo('language'), 0, 2),
                'state' => ($po5_settings['test_mode_login'] === 'DEMO' ? 'test_account' : 'live_account'),
                'redirect_uri' => get_bloginfo('url') . '/wc-api/wc_login_plationline_edit_address',
                'scope' => \urlencode('billingAddress,shippingAddress'),
                'singleAddress' => 'true', //customer can select only 1 billing address and 1 shipping address
            );
            $url = add_query_arg($data, $po::$url_login_plationline);
            echo '<a id="po6-login" href="' . $url . '"><img id="po6-logo" src="' . plugin_dir_url(__FILE__) . '../../assets/images/logo-wallet.png"><span>' . __('Import addresses from Plati.Online Account', 'plationline') . '</span></a>';
        }
    }

    public function login_plationline()
    {
        if (!empty($_GET['code'])) {
            $resources = $this->get_resources(sanitize_text_field($_GET['code']), false);
            if (!empty($resources)) {
                $this->login_user($resources);
            }
        } else {
            wp_die("PlatiOnline Login failure, no data was sent");
        }
    }

    public function import_plationline()
    {
        if (!empty($_GET['code'])) {
            $resources = $this->get_resources(sanitize_text_field($_GET['code']), true);
            if (!empty($resources)) {
                $this->login_user($resources, true);
            }
        } else {
            wp_die("PlatiOnline Login failure, no data was sent");
        }
    }

    private function import_addresses($user_id, $data)
    {
        if (!empty($data->x_address_book)) {
            $shipping_count = 0;
            $billing_count = 0;
            foreach ($data->x_address_book as $addr) {
                if ($addr->x_shipping == 0 && $billing_count == 0) {
                    update_user_meta($user_id, "billing_first_name", $addr->x_contact->f_first_name);
                    update_user_meta($user_id, "billing_last_name", $addr->x_contact->f_last_name);
                    update_user_meta($user_id, "billing_company", $addr->x_address->f_company);
                    update_user_meta($user_id, "billing_address_1", $addr->x_address->f_address);
                    update_user_meta($user_id, "billing_city", $addr->x_address->f_city);
                    update_user_meta($user_id, "billing_postcode", $addr->x_address->f_zip);
                    update_user_meta($user_id, "billing_country", $addr->x_address->f_country);
                    update_user_meta($user_id, "billing_state", $addr->x_address->f_state);
                    update_user_meta($user_id, "billing_email", sanitize_email($addr->x_contact->f_email));
                    update_user_meta($user_id, "billing_phone", $addr->x_contact->f_phone ?: $addr->x_contact->f_mobile_number);
                    $billing_count = 1;
                }

                if ($addr->x_shipping == 1 && $shipping_count == 0) {
                    update_user_meta($user_id, "shipping_first_name", $addr->x_contact->f_first_name);
                    update_user_meta($user_id, "shipping_last_name", $addr->x_contact->f_last_name);
                    update_user_meta($user_id, "shipping_company", $addr->x_address->f_company);
                    update_user_meta($user_id, "shipping_address_1", $addr->x_address->f_address);
                    update_user_meta($user_id, "shipping_city", $addr->x_address->f_city);
                    update_user_meta($user_id, "shipping_postcode", $addr->x_address->f_zip);
                    update_user_meta($user_id, "shipping_country", $addr->x_address->f_country);
                    update_user_meta($user_id, "shipping_state", $addr->x_address->f_state);
                    $shipping_count = 1;
                }

                if ($billing_count == 1 && $shipping_count == 1) {
                    break;
                }
            }
        }
    }

    private function login_user($data, $is_edit = false)
    {
        $profile_name = sanitize_user($data->x_first_name . ($data->x_last_name ? ' ' . $data->x_last_name : ''));
        $profile_email = sanitize_email($data->x_email);

        if (!email_exists($profile_email) && username_exists($profile_name)) {
            $profile_id = uniqid();
            $profile_name = $profile_name . $profile_id;
        }

        $user_id = username_exists($profile_name);

        if (!$user_id && email_exists($profile_email) === false) {
            // daca nu este client creez cont nou si ii adaug adresele selectate
            $random_password = wp_generate_password($length = 12, $include_standard_special_chars = true);
            $user_id = wc_create_new_customer($profile_email, $profile_name, $random_password);
            $user1 = get_user_by('id', $user_id);
            wp_set_current_user($user1->ID, $user1->user_login);
            $customer = new \WC_Customer($user_id);
            $customer->set_last_name($data->x_last_name);
            $customer->set_first_name($data->x_first_name);
            $customer->save();
            wp_set_auth_cookie($user1->ID);
            $user_info = get_userdata($user1->ID);
            do_action('wp_login', $user1->user_login, $user_info);
            if (!empty($data->x_payment_token)) {
                WC()->initialize_session();
                WC()->session->set('x_payment_token', $data->x_payment_token);
            }
            $this->import_addresses($user_id, $data);
        } else {
            // daca este client
            $user1 = get_user_by('email', $profile_email);
            wp_set_current_user($user1->ID, $user1->user_login);
            wp_set_auth_cookie($user1->ID);
            $user_info = get_userdata($user1->ID);
            do_action('wp_login', $user1->user_login, $user_info);
            if (!empty($data->x_payment_token)) {
                WC()->initialize_session();
                WC()->session->set('x_payment_token', $data->x_payment_token);
            }
            if ($is_edit === true) {
                $this->import_addresses($user1->get('ID'), $data);
                wp_redirect(wc_get_endpoint_url(get_option('woocommerce_myaccount_edit_address_endpoint'), '', get_permalink(wc_get_page_id('myaccount'))));
                exit;
            }
        }
        wp_redirect(get_permalink(wc_get_page_id('myaccount')));
        exit;
    }

    private function get_resources($code, $is_edit = false)
    {
        $po5_settings = (new WC_PlatiOnline())->settings;
        $po = new PO5();
        $rsa = new RSA();
        $rsa->loadKey($po5_settings['rsa_login']);
        $rsa->setEncryptionMode($rsa::ENCRYPTION_PKCS1);
        $code = \base64_encode($rsa->encrypt($code));

        $urlparts = parse_url(home_url());
        $domain = preg_replace('/www\./i', '', $urlparts['host']);

        $request_token = new Curl\Post($po::$url_login_plationline, [
            'data' => [
                'f_action' => '031',
                'f_login' => $po5_settings['merchant_id_' . \strtolower(\get_woocommerce_currency())],
                'f_website' => $domain,
                'code' => $code,
            ],
            'is_payload' => false,
        ]);

        $request_token->send();
        if ($request_token->getStatus() === 200) {
            $response = $request_token->getResponse();
            $token_data = \json_decode($response);

            $request_resources = new Curl\Post($po::$url_login_plationline, [
                'data' => [
                    'f_action' => '032',
                    'f_login' => $po5_settings['merchant_id_' . \strtolower(\get_woocommerce_currency())],
                ],
                'headers' => [
                    "Authorization: bearer " . $token_data->access_token,
                ],
                'is_payload' => false,
            ]);

            $request_resources->send();
            if ($request_resources->getStatus() === 200) {
                $response = $request_resources->getResponse();
                return \json_decode($response);
            } else {
                throw new \Exception('Could not obtain resources from PlatiOnline');
            }
        } else {
            throw new \Exception('Could not obtain token data from PlatiOnline');
        }
    }
}
