<?php
/**
 * Return warranty helper.
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */

if (! defined('ABSPATH') ) {
    exit;
}
if (! is_plugin_active('woocommerce-warranty/woocommerce-warranty.php') ) {
    return;
}
if (! is_plugin_active('woocommerce/woocommerce.php') ) {
    return;
}
/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * Sa_Return_Warranty class
 */
class Sa_Return_Warranty
{

    /**
     * Construct function.
     *
     * @return void
     */
    public function __construct()
    {

        add_filter('sAlertDefaultSettings', __CLASS__ . '::add_default_setting', 1);
        add_action('wc_warranty_settings_tabs', __CLASS__ . '::smsalertWarrantyTab');
        add_action('wc_warranty_settings_panels', __CLASS__ . '::smsalertWarrantySettingsPanels');
        add_action('admin_post_wc_warranty_settings_update', array( $this, 'updateWcWarrantySettings' ), 5);
        add_action('wp_ajax_warranty_update_request_fragment', array( $this, 'onRmaStatusUpdate' ), 0);
        add_action('wc_warranty_created', array( $this, 'onNewRmaRequest' ), 5);
    }

    /**
     * Get warranty status function.
     *
     * @return void
     */
    public static function getWarrantyStatus()
    {
        if (! class_exists('WooCommerce_Warranty') ) {
            return array();
        }

        $wc_warranty = new WooCommerce_Warranty();
        return $wc_warranty->get_default_statuses();
    }

    /**
     * Update wc warranty settings function.
     *
     * @param array $data data.
     *
     * @return void
     */
    public function updateWcWarrantySettings( $data )
    {
        $options = $_POST;
        if ('smsalert_warranty' === $options['tab'] ) {
            foreach ( $options as $name => $value ) {
                if (is_array($value) ) {
                    foreach ( $value as $k => $v ) {
                        if (! is_array($v) ) {
                            $value[ $k ] = sanitize_text_field(wp_unslash($v));
                        }
                    }
                }
                update_option($name, $value);
            }
        }
    }

    /**
     * Send rma status sms function.
     *
     * @param int    $request_id request_id.
     * @param string $status     status.
     *
     * @return void
     */
    public function sendRmaStatusSms( $request_id, $status )
    {
        $wc_warranty_checkbox = smsalert_get_option('warranty_status_' . $status, 'smsalert_warranty', '');
        $is_sms_enabled       = ( 'on' === $wc_warranty_checkbox ) ? true : false;
        if ($is_sms_enabled ) {
            $sms_content = smsalert_get_option('sms_text_' . $status, 'smsalert_warranty', '');
            $order_id    = get_post_meta($request_id, '_order_id', true);
            $rma_id      = get_post_meta($request_id, '_code', true);
            $order       = wc_get_order($order_id);
            global $wpdb;
            $products = $items = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT *
				FROM {$wpdb->prefix}wc_warranty_products
				WHERE request_id                                        = %d",
                    $request_id
                ),
                ARRAY_A
            );

            $item_name = '';
            foreach ( $products as $product ) {

                if (empty($product['product_id']) && empty($item['product_name']) ) {
                    continue;
                }

                if (0 === $product['product_id'] ) {
                    $item_name .= $item['product_name'] . ', ';
                } else {
                    $item_name .= $this->warrantyGetProductName($product['product_id']) . ', ';
                }
            }

            $item_name                  = rtrim($item_name, ', ');
            $sms_content                = str_replace('[item_name]', $item_name, $sms_content);
            $buyer_sms_data             = array();
			if ( version_compare( WC_VERSION, '7.1', '<' ) ) {
              $buyer_mob   = get_post_meta( $order_id , '_billing_phone', true );
			} else {
              $buyer_mob   = $order->get_meta('_billing_phone');
            }
            $buyer_sms_data['number']   = $buyer_mob;
            $buyer_sms_data['sms_body'] = $sms_content;
            $buyer_sms_data['rma_id']   = $rma_id;
            $buyer_sms_data['rma_status']   = $status;
            $buyer_sms_data             = WooCommerceCheckOutForm::pharseSmsBody($buyer_sms_data, $order_id);
            $message                    = ( ! empty($buyer_sms_data['sms_body']) ) ? $buyer_sms_data['sms_body'] : '';

            do_action('sa_send_sms', $buyer_mob, $message);
        }
    }
    
    /**
     * Get product name by product_id.
     *
     * @param int $product_id product id .
     *
     * @return void
     */    
    public function warrantyGetProductName( $product_id )
    {
        $product    = wc_get_product($product_id);
        $title      = $product->get_name();

        if ($product && $product->is_type('variation') ) {
            $title = $product->get_title();
        }
        return $title;
    }

    /**
     * On new rma request function.
     *
     * @param int $warranty_id warranty id .
     *
     * @return void
     */
    public function onNewRmaRequest( $warranty_id )
    {
        $this->sendRmaStatusSms($warranty_id, 'new');
    }

    /**
     * On rma status update function.
     *
     * @return void
     */
    public function onRmaStatusUpdate()
    {
        $request_id = isset($_POST['request_id']) ? sanitize_text_field(wp_unslash($_POST['request_id'])) : '';
        $status     = isset($_POST['status']) ? sanitize_text_field(wp_unslash($_POST['status'])) : '';

        $this->sendRmaStatusSms($request_id, $status);
    }

    /**
     * Smsalert warranty tab function.
     *
     * @return void
     */
    public static function smsalertWarrantyTab()
    {
        $active_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : '';
        ?>
        <a href="admin.php?page=warranties-settings&tab=smsalert_warranty" class="nav-tab <?php echo ( 'smsalert_warranty' === $active_tab ) ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('SMS Alert', 'wc_warranty'); ?></a>
        <?php
    }

    /**
     * Smsalert warranty settings panels.
     *
     * @return void
     */
    public static function smsalertWarrantySettingsPanels()
    {
        $active_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : '';

        if ('smsalert_warranty' === $active_tab ) {
            $return_warranty_param = array(
            'checkTemplateFor' => 'return_warranty',
            'templates'        => self::getReturnWarrantyTemplates(),
            );
            get_smsalert_template('views/message-template.php', $return_warranty_param);
        }
    }

    /**
     * Add default settings to savesetting in setting-options.
     *
     * @param array $defaults defaults.
     *
     * @return array
     */
    public static function add_default_setting( $defaults = array() )
    {
        $wc_warrant_status = self::getWarrantyStatus();

        foreach ( $wc_warrant_status as $ks                           => $vs ) {
            $vs = str_replace(' ', '-', strtolower($vs));
            $defaults['smsalert_warranty'][ 'warranty_status_' . $vs ] = 'off';
            $defaults['smsalert_warranty']['sms_text_'][ $vs ]         = '';
        }
        return $defaults;
    }
    
    /**
     * Get Return Warranty Templates.
     *
     * @return array
     */
    public static function getReturnWarrantyTemplates()
    {
        $wc_warrant_status = self::getWarrantyStatus();
        $variables         = array(
        '[order_id]'           => 'Order Id',
        '[rma_number]'         => 'RMA Number',
        '[rma_status]'         => 'RMA Status',
        '[order_amount]'       => 'Order Total',
        '[billing_first_name]' => 'First Name',
        '[item_name]'          => 'Product Name',
        '[store_name]'         => 'Store Name',
        );
        $templates         = array();

        foreach ( $wc_warrant_status as $ks                           => $vs ) {

            $vs               = str_replace(' ', '-', strtolower($vs));
            $wc_warranty_text = smsalert_get_option('sms_text_' . $vs, 'smsalert_warranty', '');
            $current_val      = smsalert_get_option('warranty_status_' . $vs, 'smsalert_warranty', 'on');

            $checkbox_name_id  = 'smsalert_warranty[warranty_status_' . $vs . ']';
            $text_area_name_id = 'smsalert_warranty[sms_text_' . $vs . ']';

            $text_body = smsalert_get_option('sms_text_' . $vs, 'smsalert_warranty', '') ? smsalert_get_option('sms_text_' . $vs, 'smsalert_warranty', '') : SmsAlertMessages::showMessage('DEFAULT_WARRANTY_STATUS_CHANGED');

            $templates[ $ks ]['title']          = 'When RMA is ' . ucwords($vs);
            $templates[ $ks ]['enabled']        = $current_val;
            $templates[ $ks ]['status']         = $ks;
            $templates[ $ks ]['text-body']      = $text_body;
            $templates[ $ks ]['checkboxNameId'] = $checkbox_name_id;
            $templates[ $ks ]['textareaNameId'] = $text_area_name_id;
            $templates[ $ks ]['token']          = $variables;
        }
        return $templates;
    }
}
    new Sa_Return_Warranty();
?>