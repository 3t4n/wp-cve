<?php
/**
 * @package blazing-shipment-tracking
 */

/**
 * Plugin Name: BLAZING Shipment Tracking
 * Version: 2.1.0
 * Plugin URI: http://blazingspider.com/plugins/blazing-woocommerce-shipment-tracking
 * Description:  Add tracking number and courier name to WooCommerce order,
 *      display tracking info at order history page,
 *      auto import tracking numbers to BS_Shipment_Tracking.
 * Author: Massoud Shakeri
 * Author URI: http://blazingspider.com/
 * License: GPL v3
 */

/**
 * BLAZING Shipment Tracking Plugin
 * Copyright (C) 2017, Blazing Spider Web Solutions - design@blazingspider.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Required functions
 */
if (!function_exists('is_woocommerce_active'))
    require_once('bst-tracking-functions.php');


/**
 * Plugin updates
 */

if (is_woocommerce_active()) {

    /**
     * BS_Shipment_Tracking class
     */
    if (!class_exists('BS_Shipment_Tracking')) {
        final class BS_Shipment_Tracking
        {
            private $couriers = array();
            private $bst_fields = array();
            private $use_track_button = false;

            protected static $_instance = null;

            public static function instance()
            {
                if (is_null(self::$_instance)) {
                    self::$_instance = new self();
                }
                return self::$_instance;
            }


            /**
             * Constructor
             */
            public function __construct()
            {
                $this->includes();

                // $this->api = new BS_Shipment_Tracking_API();

                $options = get_option('bst_option_name');
                if ($options) {
                    // add_action('admin_print_scripts', array(&$this, 'library_scripts'));
                    add_action('in_admin_footer', array(&$this, 'include_footer_script'));
                    // add_action('admin_print_styles', array(&$this, 'admin_styles'));
                    add_action('add_meta_boxes', array(&$this, 'add_meta_box'));
                    add_action('woocommerce_process_shop_order_meta', array(&$this, 'save_meta_box'), 0, 2);
                    add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
                    $couriers_text = isset($options['couriers']) ?  $options['couriers'] :
                        '[&#10;  {&#10;    "slug": "canada-post", &#10;    "name": "Canada Post",&#10;    "url": "https://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber={tracking_number}&LOCALE=en"&#10;  },&#10;  {&#10;    "slug": "fedex",&#10;    "name": "FedEx",&#10;    "url": "https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber={tracking_number}"&#10;  },&#10;  {&#10;    "slug": "purolator",&#10;    "name": "Purolator",&#10;    "url": "https://www.purolator.com/purolator/ship-track/tracking-summary.page?pin={tracking_number}"&#10;  }&#10;]';

                    $this->couriers = json_decode(htmlspecialchars_decode($couriers_text), true);


                    if (isset($options['use_track_button'])) {
                        $this->use_track_button = $options['use_track_button'];
                    } else {
                        $this->use_track_button = false;
                    }

                    add_action('woocommerce_view_order', array(&$this, 'display_tracking_info'));
                    add_action('woocommerce_email_before_order_table', array(&$this, 'email_display'));
                    add_action( 'wp_ajax_bs_ship_track_email', array(&$this, 'send_tracking_email' ));

                }
                add_filter( 'woocommerce_email_classes', array(&$this, 'add_tracking_order_woocommerce_email' ));

                register_activation_hook(__FILE__, array($this, 'install'));
            }

            /**
             *  Add a custom email to the list of emails WooCommerce should load
             *
             * @since 0.1
             * @param array $email_classes available email classes
             * @return array filtered available email classes
             */
            public function add_tracking_order_woocommerce_email( $emails ) {

                // include our custom email class
                require_once( 'api/class-bst-order-tracking-email.php' );

                // add the email class to the list of email classes that WooCommerce loads
                $emails['BST_Tracking_Order_Email'] = new BST_Tracking_Order_Email();
                return $emails;

            }


            public function send_tracking_email() {
                global $wpdb; // this is how you get access to the database
                check_ajax_referer( 'send-tracking-email', 'security', true );

                $id = $_POST['id'];
                // $whatever += 10;
                // echo $whatever;

                $result = false;
                $mailer = WC()->mailer();
                $mails = $mailer->get_emails();
                if ( ! empty( $mails ) ) {
                    foreach ( $mails as $mail ) {
                        if ( $mail->id == 'BST_Tracking_Order_Email' ) {
                           $result = $mail->trigger( $id );
                        }
                     }
                }
                if ( $result ) {
                    echo "Email is Sent to the customer!";
                }
                else {
                    echo "Could not send email!";
                }
                //do_action( 'woocommerce_tracking_number_notification', $id );
                wp_die(); // this is required to terminate immediately and return a proper response
            }

            public function install()
            {
                global $wp_roles;

                if (class_exists('WP_Roles')) {
                    if (!isset($wp_roles)) {
                        $wp_roles = new WP_Roles();
                    }
                }

                if (is_object($wp_roles)) {
                    $wp_roles->add_cap('administrator', 'manage_bs_ship_track');
                }
            }

            private function includes()
            {
                include_once('bst-tracking-fields.php');
                $this->bst_fields = $bst_fields;

                include_once('class-bst-tracking-api.php');
                include_once('class-bst-tracking-settings.php');
            }

            /**
             * Localisation
             */
            public function load_plugin_textdomain()
            {
                load_plugin_textdomain('bs_ship_track', false, dirname(plugin_basename(__FILE__)) . '/languages/');
            }

            // public function admin_styles()
            // {
            //     wp_enqueue_style('bst_styles_chosen', plugins_url(basename(dirname(__FILE__))) . '/assets/plugin/chosen/chosen.min.css');
            //     wp_enqueue_style('bst_styles', plugins_url(basename(dirname(__FILE__))) . '/assets/css/admin.css');
            // }

            // public function library_scripts()
            // {
            //     wp_enqueue_script('bst_styles_chosen_jquery', plugins_url(basename(dirname(__FILE__))) . '/assets/plugin/chosen/chosen.jquery.min.js');
            //     wp_enqueue_script('bst_styles_chosen_proto', plugins_url(basename(dirname(__FILE__))) . '/assets/plugin/chosen/chosen.proto.min.js');
            // }
            public function include_footer_script()
            {
                wp_enqueue_script('bst_script_footer', plugins_url(basename(dirname(__FILE__))) . '/assets/js/footer.js', true);
            }

            /**
             * Add the meta box for shipment info on the order page
             *
             * @access public
             */
            public function add_meta_box()
            {
                add_meta_box('woocommerce-bs_ship_track', __('Blazing Shipment Tracking', 'bs_ship_track'), array(&$this, 'meta_box'), 'shop_order', 'side', 'high');
            }

            /**
             * Show the meta box for shipment info on the order page
             *
             * @access public
             */
            public function meta_box()
            {

                // just draw the layout, no data
                global $post;

                $selected_provider = get_post_meta($post->ID, '_bst_tracking_provider', true);

                echo '<div id="bst_wrapper">';

                echo '<p class="form-field"><label for="bst_tracking_provider">' . __('Courier:', 'bs_ship_track') . '</label><br/><select id="bst_tracking_provider" name="bst_tracking_provider" class="chosen_select" style="width:100%">';
                if ($selected_provider == '') {
                    $selected_text = 'selected="selected"';
                } else {
                    $selected_text = '';
                }
                echo '<option disabled ' . $selected_text . ' value="">Please Select</option>';
                echo '</select>';
                echo '<br><a href="options-general.php?page=bs_ship_track-setting-admin">Update courier list</a>';
                echo '<input type="hidden" id="bst_tracking_provider_hidden" value="' . $selected_provider . '"/>';
                $couriers_list = '';
                foreach ($this->couriers as &$c) {
                    $couriers_list .= $c['name'] . '|' . $c['slug'] . ',';
                }
                $couriers_list = rtrim($couriers_list,',');

                echo '<input type="hidden" id="bst_couriers_selected" value="' . $couriers_list . '"/>';
                echo '<input type="hidden" id="bst_order_id" value="' . $post->ID . '"/>';

                foreach ($this->bst_fields as $field) {
                    if ($field['type'] == 'date') {
                        woocommerce_wp_text_input(array(
                            'id' => $field['id'],
                            'label' => __($field['label'], 'bs_ship_track'),
                            'placeholder' => $field['placeholder'],
                            'description' => $field['description'],
                            'class' => $field['class'],
                            'value' => ($date = get_post_meta($post->ID, '_' . $field['id'], true)) ? date('Y-m-d', $date) : ''
                        ));
                    } else {
                        woocommerce_wp_text_input(array(
                            'id' => $field['id'],
                            'label' => __($field['label'], 'bs_ship_track'),
                            'placeholder' => $field['placeholder'],
                            'description' => $field['description'],
                            'class' => $field['class'],
                            'value' => get_post_meta($post->ID, '_' . $field['id'], true),
                        ));
                    }
                }
                echo '<input type="button" style="margin-left:auto;margin-right:auto;display:block;" class="button tracking_email button-primary" value="Email Tracking info">';
                woocommerce_wp_hidden_input( array(
                    'id'            => 'blazing_tracking_email_nonce',
                    'value'         => wp_create_nonce( 'send-tracking-email' )
                ) );

//
//				woocommerce_wp_text_input(array(
//					'id' => 'bst_tracking_provider_name',
//					'label' => __('', 'bs_ship_track'),
//					'placeholder' => '',
//					'description' => '',
//					'class' => 'hidden',
//					'value' => get_post_meta($post->ID, '_bst_tracking_provider_name', true),
//				));
//
//				woocommerce_wp_text_input(array(
//					'id' => 'bst_tracking_number',
//					'label' => __('Tracking number:', 'bs_ship_track'),
//					'placeholder' => '',
//					'description' => '',
//					'value' => get_post_meta($post->ID, '_bst_tracking_number', true),
//				));
//
//				woocommerce_wp_text_input(array(
//					'id' => 'bst_tracking_shipdate',
//					'label' => __('Date shipped:', 'bs_ship_track'),
//					'placeholder' => 'YYYY-MM-DD',
//					'description' => '',
//					'class' => 'date-picker-field hidden-field',
//					'value' => ($date = get_post_meta($post->ID, '_bst_tracking_shipdate', true)) ? date('Y-m-d', $date) : ''
//				));
//
                echo '</div>';
            }

            /**
             * Order Downloads Save
             *
             * Function for processing and storing all order downloads.
             */
            public function save_meta_box($post_id, $post)
            {
                if (isset($_POST['bst_tracking_number'])) {
//
//                    // Download data
                    $tracking_provider = woocommerce_clean($_POST['bst_tracking_provider']);
                    $tracking_number = woocommerce_clean($_POST['bst_tracking_number']);
                    $tracking_provider_name = woocommerce_clean($_POST['bst_tracking_provider_name']);
//                    $shipdate = woocommerce_clean(strtotime($_POST['bst_tracking_shipdate']));
//
//                    // Update order data
                    update_post_meta($post_id, '_bst_tracking_provider', $tracking_provider);
                    update_post_meta($post_id, '_bst_tracking_number', $tracking_number);
                    update_post_meta($post_id, '_bst_tracking_provider_name', $tracking_provider_name);
//                    update_post_meta($post_id, '_bst_tracking_shipdate', $shipdate);

                    // foreach ($this->bst_fields as $field) {
                    //     if ($field['type'] == 'date') {
                    //         update_post_meta($post_id, '_' . $field['id'], woocommerce_clean(strtotime($_POST[$field['id']])));
                    //     } else {
                    //         update_post_meta($post_id, '_' . $field['id'], woocommerce_clean($_POST[$field['id']]));
                    //     }
                    // }
                }
            }

            /**
             * Display Shipment info in the frontend (order view/tracking page).
             *
             * @access public
             */
            function display_tracking_info($order_id, $for_email = false)
            {

                $values = array();
                foreach ($this->bst_fields as $field) {
                    $values[$field['id']] = get_post_meta($order_id, '_' . $field['id'], true);
                    if ($field['type'] == 'date' && $values[$field['id']]) {
                        $values[$field['id']] = date_i18n(__('l jS F Y', 'wc_shipment_tracking'), $values[$field['id']]);
                    }
                }
                $values['bst_tracking_provider'] = get_post_meta($order_id, '_bst_tracking_provider', true);
                $values['bst_shipping_postcode'] = get_post_meta( $order_id, '_shipping_postcode', true );
                if ( !isset($values['bst_shipping_postcode']) || trim($values['bst_shipping_postcode']) === '' ) {
                    $values['bst_shipping_postcode'] = get_post_meta( $order_id, '_billing_postcode', true );
                }
                $values['bst_shipping_country'] = get_post_meta( $order_id, '_shipping_country', true );
                if ( !isset($values['bst_shipping_country']) || trim($values['bst_shipping_country']) === '' ) {
                    $values['bst_shipping_country'] = get_post_meta( $order_id, '_billing_country', true );
                }

                if (!$values['bst_tracking_provider'])
                    return;

                if (!$values['bst_tracking_number'])
                    return;

                $provider_url = '';
                if ( array_key_exists('bst_tracking_provider', $values) && $values['bst_tracking_provider'] != "" ) {
                    foreach ($this->couriers as &$c) {
                        if ( $c['slug'] == $values['bst_tracking_provider'] ) {
                            if ( array_key_exists('url', $c) ) {
                                $provider_url = str_replace( "{tracking_number}", $values['bst_tracking_number'], $c['url']);
                                $provider_url = str_replace( "{shipping_postcode}", $values['bst_shipping_postcode'], $provider_url);
                                $provider_url = str_replace( "{shipping_country}", $values['bst_shipping_country'], $provider_url);
                                break;
                            }
                        }
                    }
                }
                $provider_url = str_replace(' ', '', $provider_url);

                $options = get_option('bst_option_name');
                if (array_key_exists('track_message_1', $options) && array_key_exists('track_message_2', $options)) {
                    $track_message_1 = $options['track_message_1'];
                    $track_message_2 = $options['track_message_2'];
                } else {
                    $track_message_1 = 'Your order was shipped via ';
                    $track_message_2 = 'Tracking number is ';
                }

                //$required_fields_values = array();

                echo $track_message_1 . $values['bst_tracking_provider_name'] . '<br/>' . $track_message_2 . "   " . "<strong>" . $values['bst_tracking_number'] . "</strong>" . "<br />";

                if (/*!$for_email && */ $this->use_track_button && $provider_url != '' ) {
                    $btn = "<a style=\"margin: 20px;\" href=\"" . $provider_url . "\" target=\"_blank\" class=\"button primary is-blue is-medium\"><span>You Can Track Your Order Here</span></a>";
                    echo $btn;
                    // $this->display_track_button($values['bst_tracking_provider'], $values['bst_tracking_number'], $required_fields_values);
                }

                //-------------------------------------------------------------------------------------
                /*
                                $tracking_provider = get_post_meta($order_id, '_bst_tracking_provider', true);
                                $tracking_number = get_post_meta($order_id, '_bst_tracking_number', true);
                                $tracking_provider_name = get_post_meta($order_id, '_bst_tracking_provider_name', true);
                                $date_shipped = get_post_meta($order_id, '_bst_tracking_shipdate', true);

                                if (!$tracking_provider)
                                    return;

                                if (!$tracking_number)
                                    return;

                                $provider_name = $tracking_provider_name;
                                $provider_required_fields = explode(",", $tracking_required_fields);

                                $date_shipped_str = '';
                                $postcode_str = '';
                                $account_str = '';

                                foreach ($provider_required_fields as $field) {
                                    if ($field == 'tracking_ship_date') {
                                        if ($date_shipped) {
                                            $date_shipped_str = '&nbsp;' . sprintf(__('on %s', 'wc_shipment_tracking'), date_i18n(__('l jS F Y', 'wc_shipment_tracking'), $date_shipped));
                                        }
                                    } else if ($field == 'tracking_postal_code') {
                                        if ($postcode) {
                                            $postcode_str = '&nbsp;' . sprintf('The postal code is %s.', $postcode);
                                        }
                                    } else if ($field == 'tracking_account_number') {
                                        if ($account) {
                                            $account_str = '&nbsp;' . sprintf('The account is %s.', $account);
                                        }
                                    }
                                }

                                $provider_name = '&nbsp;' . __('via', 'wc_shipment_tracking') . ' <strong>' . $provider_name . '</strong>';

                                echo wpautop(sprintf(__('Your order was shipped%s%s. Tracking number is %s.%s%s', 'wc_shipment_tracking'), $date_shipped_str, $provider_name, $tracking_number, $postcode_str, $account_str));

                                if (!$for_email && $this->use_track_button) {
                                    $this->display_track_button($tracking_provider, $tracking_number);
                                }
                */

            }

            /**
             * Display shipment info in customer emails.
             *
             * @access public
             * @return void
             */
            function email_display($order)
            {
                $this->display_tracking_info($order->id, true);
            }

            private function display_track_button($tracking_provider, $tracking_number, $required_fields_values)
            {

                if (count($required_fields_values)) {
                    $tracking_number = $tracking_number . ':' . join(':', $required_fields_values);
                }

                $temp_url = '';
                $temp_slug = ' data-slug="' . $tracking_provider . '"';

                $track_button = '<div id="as-root"></div><div class="as-track-button"' . $temp_slug . ' data-tracking-number="' . $tracking_number . $temp_url .'" data-support="true" data-width="400" data-size="normal" data-hide-tracking-number="true"></div>';
                echo wpautop(sprintf('%s', $track_button));
                echo "<br><br>";
            }
        }

        if (!function_exists('get_BS_Shipment_Tracking_Instance')) {
            function get_BS_Shipment_Tracking_Instance()
            {
                return BS_Shipment_Tracking::Instance();
            }
        }
    }

    /**
     * Register this class globally
     */
    $GLOBALS['bs_ship_track'] = get_BS_Shipment_Tracking_Instance();

}
