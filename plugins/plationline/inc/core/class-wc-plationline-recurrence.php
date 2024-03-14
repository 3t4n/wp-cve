<?php

namespace PlatiOnlinePO6\Inc\Core;

/**
 * @link              https://plati.online
 * @since             6.1.0
 * @package           PlatiOnlinePO6
 *
 */
class WC_PlatiOnline_Recurrence extends \WC_Payment_Gateway
{
    public static $po_recurrence_types;

    public function __construct()
    {
        self::$po_recurrence_types = array(
            'plationline_recurrence_master' => __('PlatiOnline master recurrent order', 'plationline'),
            'plationline_recurrence_child' => __('PlatiOnline child recurring order', 'plationline'),
        );
        $this->id = 'plationline_recurrence';
        $this->method_title = __('PlatiOnline Recurring Payments', 'plationline');
        $this->method_description = __('Process recurrent payments with PlatiOnline', 'plationline');
        $this->has_fields = false;
        $this->init_form_fields();
        $this->init_settings();

        $this->icon = (!empty($this->settings['show_logos']) && $this->settings['show_logos'] === 'yes') ? 'https://media.plationline.ro/images/plati-online-recurenta.png' : '';
        $this->title = $this->settings['title'];
        $this->description = $this->settings['description'];
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

        add_action('woocommerce_after_checkout_validation', array($this, 'plationline_recurrence_validate_order'), 10, 2);
        add_action('woocommerce_checkout_create_order', array($this, 'plationline_save_order_payment_type_meta_data'), 10, 2);
        add_filter('woocommerce_available_payment_gateways', array($this, 'plationline_recurrence_filter_woocommerce_available_payment_gateways'), 10, 1);
        add_action('woocommerce_order_details_after_order_table', array($this, 'plationline_order_details_recurrence'));
        add_filter('woocommerce_thankyou_' . $this->id, array('PlatiOnlinePO6\Inc\Core\WC_PlatiOnline_Process', 'po_order_received'), 10, 1);

        if (!$this->is_valid_for_use()) {
            $this->enabled = 'no';
        }
    }

    public function init_settings()
    {
        parent::init_settings();
    }

    public function is_valid_for_use()
    {
        return in_array(get_woocommerce_currency(), array('RON', 'EUR', 'USD'));
    }

    public function admin_options()
    {
        echo '<h2>' . \esc_html($this->get_method_title());
        \wc_back_link(__('Return to payments', 'woocommerce'), \admin_url('admin.php?page=wc-settings&tab=checkout'));
        echo '</h2>';
        echo '<div class="inline error"><p>' . __('After enabling recurrent payments you have to edit your products and check the <b>"This product supports PlatiOnline Recurring payments"</b> option so that product can be part of a recurrent cart. During checkout, the system checks if all products in customer\'s cart have PlatiOnline recurrence support and displays the recurrent payment option. The customer will have to choose one of the recurrence <b>frequencies</b> and <b>durations</b> you setup below in the checkout process. When the recurrence takes place, we will copy the initial customer order and create a new order that can be processed from your shop\'s admin interface.', 'plationline') . '</p></div>';
        echo '<table class="form-table">';
        // Generate the HTML For the settings form.
        $this->generate_settings_html();
        echo '</table>';
    }

    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Active', 'plationline'),
                'type' => 'checkbox',
                'label' => __('Activate recurrent payments by PlatiOnline', 'plationline'),
                'default' => 'no',
            ),
            'title' => array(
                'title' => __('Payment method title', 'plationline'),
                'type' => 'text',
                'description' => __('This title will be shown in frontend to the customer', 'plationline'),
                'default' => __('Recurrent payments by PlatiOnline', 'plationline'),
            ),
            'description' => array(
                'title' => __('Payment method description', 'plationline'),
                'type' => 'textarea',
                'default' => __('You will be redirected to PlatiOnline page', 'plationline'),
            ),
            'show_logos' => array(
                'title' => __('Show payment logos in frontend', 'plationline'),
                'type' => 'checkbox',
                'label' => __('Show logos', 'plationline'),
                'description' => __('Show PlatiOnline Recurrence logos in checkout', 'plationline'),
                'default' => 'yes',
            ),
            'recurrence_frequency' => array(
                'title' => __('Recurrence frequency', 'plationline'),
                'type' => 'multiselect',
                'description' => __('Please select the recurrence frequencies you want to show in checkout process. You can choose multiple frequencies.', 'plationline'),
                'default' => 3,
                'options' => array(
                    1 => __('Weekly', 'plationline'),
                    2 => __('Every 2 weeks', 'plationline'),
                    3 => __('Monthly', 'plationline'),
                    4 => __('Quarterly', 'plationline'),
                    5 => __('Semestrial', 'plationline'),
                    8 => __('Annually', 'plationline'),
                ),
            ),
            'recurrence_duration' => array(
                'title' => __('Recurrence duration', 'plationline'),
                'type' => 'multiselect',
                'description' => __('Please select the recurrence duration you want to show in checkout process.', 'plationline'),
                'default' => 12,
                'options' => array(
                    6 => \sprintf(__('%d months', 'plationline'), 6),
                    12 => \sprintf(__('%d months', 'plationline'), 12),
                    24 => \sprintf(__('%d months', 'plationline'), 24),
                    36 => \sprintf(__('%d months', 'plationline'), 36),
                    48 => \sprintf(__('%d months', 'plationline'), 48),
                    60 => \sprintf(__('%d months', 'plationline'), 60),
                ),
            ),
        );
    }

    /**
     *  There are no payment fields for plationline, but we want to show the description if set.
     **/
    public function payment_fields()
    {

        if (!empty($this->settings['recurrence_frequency']) && !empty($this->settings['recurrence_duration'])) {
            $options = array();
            foreach ($this->form_fields['recurrence_frequency']['options'] as $key => $value) {
                if (\in_array($key, $this->settings['recurrence_frequency'])) {
                    $options[$key] = $value;
                }
            }
            $option_keys = \array_keys($options);
            \woocommerce_form_field('recurrence_frequency', array(
                'type' => 'select',
                'label' => __('Select recurrence frequency', 'plationline'),
                'options' => $options,
                'required' => true,
            ), \reset($option_keys));

            $options = array();
            foreach ($this->form_fields['recurrence_duration']['options'] as $key => $value) {
                if (\in_array($key, $this->settings['recurrence_duration'])) {
                    $options[$key] = $value;
                }
            }
            $option_keys = \array_keys($options);

            \woocommerce_form_field('recurrence_duration', array(
                'type' => 'select',
                'label' => __('Select recurrence duration', 'plationline'),
                'options' => $options,
                'required' => true,
            ), \reset($option_keys));
        }

        if ($this->description) {
            echo \wpautop(\wptexturize($this->description));
        }
    }

    public function plationline_recurrence_filter_woocommerce_available_payment_gateways($gateways)
    {
        if (is_a(\WC()->cart, \WC_Cart::class)) {
            if (!empty(\WC()->cart->recurring_carts)) {
                // daca e cos cu Woocommerce Subscriptions elimin plata recurenta simulata de noi
                unset($gateways[$this->id]);
            } else {
                $products = \WC()->cart->get_cart_contents();
                if (!empty($products)) {
                    foreach ($products as $product) {
                        if (!\wc_string_to_bool(\get_post_meta($product['product_id'], '_plationline_enable_recurrence', true))) {
                            unset($gateways[$this->id]);
                            break;
                        }
                    }
                }
            }
        }
        return $gateways;
    }

    public function plationline_order_details_recurrence($order)
    {
        if ($order->get_payment_method() == $this->id) {
            $plationline_transaction_type = $order->get_meta('_plationline_transaction_type');
            $plationline_recurrence_frequency = $order->get_meta('_plationline_recurrence_frequency');
            $plationline_recurrence_duration = $order->get_meta('_plationline_recurrence_duration');

            echo '<p>' . __('PlatiOnline Recurrent Transaction Type', 'plationline') . ': <b>' . WC_PlatiOnline_Recurrence::$po_recurrence_types[$plationline_transaction_type] . '</b></p>';
            if (!empty($plationline_recurrence_frequency)) {
                echo '<p>' . __('Recurrence frequency', 'plationline') . ': <b>' . $this->form_fields['recurrence_frequency']['options'][$plationline_recurrence_frequency] . '</b></p>';
            }

            if (!empty($plationline_recurrence_duration)) {
                echo '<p>' . __('Recurrence duration', 'plationline') . ': <b>' . $this->form_fields['recurrence_duration']['options'][$plationline_recurrence_duration] . '</b></p>';
            }

            if ($plationline_transaction_type == 'plationline_recurrence_child' && !empty($order->get_meta('_plationline_recurrence_master_order_id'))) {
                $master_order = \wc_get_order(absint($order->get_meta('_plationline_recurrence_master_order_id')));
                if (!empty($master_order)) {
                    $url = $master_order->get_view_order_url();
                    echo '<p>' . __('PlatiOnline master order', 'plationline') . ': <a href="' . $url . '"><b>#' . $order->get_meta('_plationline_recurrence_master_order_id') . '</b></a></p>';
                    echo '<p>' . __('PlatiOnline master transaction ID', 'plationline') . ': <a href="' . $url . '"><b>#' . $order->get_meta('_plationline_recurrence_master_transaction_id') . '</b></a></p>';
                }
            }

            if ($plationline_transaction_type == 'plationline_recurrence_master') {
                $child_order_ids = $order->get_meta('_plationline_recurrence_child_orders');
                if (!empty($child_order_ids)) {
                    \krsort($child_order_ids);
                    echo '<h5>' . __('PlatiOnline child recurrent orders', 'plationline') . '</h5>';
                    echo '<table class="woocommerce-table shop_table order_details">';
                    echo '<tr>
							<th>' . __('Order number', 'plationline') . '</th>
							<th>' . __('Order date purchased', 'plationline') . '</th>
							<th>' . __('Order status', 'plationline') . '</th>
							<th>' . __('Order transaction ID', 'plationline') . '</th>
							<th>' . __('View order', 'plationline') . '</th>
						</tr>';
                    foreach ($child_order_ids as $child_order_id => $child_trans_id) {
                        $child_order = \wc_get_order(absint($child_order_id));
                        if (!empty($child_order)) {
                            echo '<tr>';
                            echo '<td>#' . $child_order_id . '</td>';
                            echo '<td>' . $child_order->get_date_created()->format('d-m-Y') . '</td>';
                            echo '<td>' . \wc_get_order_status_name($child_order->get_status()) . '</td>';
                            echo '<td>#' . $child_trans_id . '</td>';
                            $url = $child_order->get_view_order_url();
                            echo '<td>' . \sprintf('<a href="%s"><b>%s</b></a>', $url, __('View child order', 'plationline')) . '</td>';
                            echo '</tr>';
                        }
                    }
                    echo '</table>';
                }
            }
        }
    }

    public function plationline_save_order_payment_type_meta_data($order, $data)
    {
        if ($data['payment_method'] === $this->id && isset($_POST['recurrence_frequency']) && isset($_POST['recurrence_duration'])) {
            $order->update_meta_data('_plationline_transaction_type', $this->id . '_master');
            $order->update_meta_data('_plationline_recurrence_frequency', \esc_attr($_POST['recurrence_frequency']));
            $order->update_meta_data('_plationline_recurrence_duration', \esc_attr($_POST['recurrence_duration']));
        }
    }

    public function plationline_recurrence_validate_order($fields, $errors)
    {
        if ($fields['payment_method'] == $this->id) {
            if (!isset($_POST['recurrence_frequency'])) {
                $errors->add('validation', __('Please select recurrence frequency', 'plationline'));
            }
            if (!\in_array(esc_attr($_POST['recurrence_frequency']), $this->settings['recurrence_frequency'])) {
                $errors->add('validation', __('Selected recurrence frequency is invalid', 'plationline'));
            }
            if (!isset($_POST['recurrence_duration'])) {
                $errors->add('validation', __('Please select recurrence duration', 'plationline'));
            }
            if (!\in_array(esc_attr($_POST['recurrence_duration']), $this->settings['recurrence_duration'])) {
                $errors->add('validation', __('Selected recurrence duration is invalid', 'plationline'));
            }
        }
    }

    /**
     * Process the payment and return the result
     **/
    public function process_payment($order_id)
    {
        $order = new \WC_Order($order_id);
        $order->update_status('pending', __('PO Pending Authorization', 'plationline'));
        return array('result' => 'success', 'redirect' => $order->get_checkout_payment_url(true));
    }
}
