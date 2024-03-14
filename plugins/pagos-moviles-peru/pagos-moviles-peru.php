<?php

/**
 * Plugin Name: Pagos Móviles Perú
 * Plugin URI: https://wordpress.org/plugins/pagos-moviles-peru
 * Description: El plugin "Pagos Móviles Perú" permite agregar YAPE como forma de pago a su tiendas WooCommerce. YAPE es un medio de pago peruano, por lo que el plugin es para Perú, YAPE permite pagos con su telefono Smartphone.
 * Version: 1.1
 * Author: AQP hosting
 * Author URI: https://alojamientowp.org/
 * Text Domain: pagos-moviles-peru
 * Domain Path: /languages
 **/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    echo "WooCommerce not found or disabled";
    exit;
}

if (!defined('ABSPATH') && !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins')))) {
    exit;
}

global $wpdb;

define('PAGO_MOVILES_PERU_URI', __FILE__);
define('PAGO_MOVILES_PERU_PATH', plugin_dir_path(__FILE__));
define('PAGO_MOVILES_PERU_VERSION', '1.1');
define('PAGO_MOVILES_PERU_ASSETS', plugin_dir_url(__FILE__) . 'assets/');
define('PAGO_MOVILES_PERU_TEXT_DOMAIN', basename(dirname(__FILE__)) . '/languages/');
define('PAGO_MOVILES_PERU_DB_TABLE', $wpdb->prefix . 'pago_moviles_peru');

require_once PAGO_MOVILES_PERU_PATH . 'class-init.php';

add_filter( 'woocommerce_payment_gateways', 'pago_moviles_peru_add_gateway_class' );
function pago_moviles_peru_add_gateway_class( $gateways ) {
	$gateways[] = 'PagoMovilesPeru_Gateway';
	return $gateways;
}

add_action( 'plugins_loaded', 'pago_moviles_peru_init_gateway_class' );
function pago_moviles_peru_init_gateway_class() {
    class PagoMovilesPeru_Gateway extends WC_Payment_Gateway
    {
        const ID = 1;
        public $domain;
    
         public function __construct()
         {
            $this->id = 'pago_moviles_peru';
            $this->icon = apply_filters( 'woocommerce_gateway_icon', PAGO_MOVILES_PERU_ASSETS.'icon.png' );
            $this->has_fields = true;
            $this->domain = 'pagos-moviles-peru';
            $this->method_title = 'YAPE';
            $this->method_description = __( 'Pay using YAPE', 'pagos-moviles-peru' );
         
            $this->supports = array(
                'products'
            );
         
            $this->init_form_fields();
            $this->init_settings();

            $this->title = $this->get_option('title');
            $this->phone = $this->get_option('phone');
            $this->needcapture = $this->get_option('needcapture');
            $this->instructions = $this->get_option('instructions');
         
            add_action( 'woocommerce_before_order_notes', array( $this, 'custom_checkout_field' ) );
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'show_yape_field_order' ), 10, 1 );
            add_action( 'woocommerce_email_after_order_table', array( $this, 'show_yape_field_emails' ), 20, 4 );
            add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
         }
         
         public function custom_checkout_field($checkout) {
            echo wp_kses('<div id="yape_img_field"><input type="hidden" name="yape_img" id="yape_img"></div>', array(
                'div' => array(
                    'id' => array(),
                ),
                'input' => array(
                    'type' => array(),
                    'name' => array(),
                    'id' => array(),
                    'value' => array(),
                ),
            ));
         }
 
         public function init_form_fields()
         {
            $this->form_fields = array(
                'title' => array(
                    'title'       => __( 'Title', 'pagos-moviles-peru' ),
                    'type'        => 'text',
                    'description' => __( 'This is the title of the payment method on the customer checkout', 'pagos-moviles-peru' ),
                    'default'     => 'YAPE',
                    'desc_tip'    => false,
                ),
                'instructions' => array(
                    'title'       => __( 'Instructions', 'pagos-moviles-peru' ),
                    'type'        => 'text',
                    'description' => __( 'A short instruction to show at the bottom of the QR code', 'pagos-moviles-peru' ),
                    'default'     => '',
                    'desc_tip'    => false,
                ),
                'phone' => array(
                    'title'       => __( 'Phone', 'pagos-moviles-peru' ),
                    'type'        => 'text',
                    'description' => __( 'This is the phone number associated to YAPE account', 'pagos-moviles-peru' ),
                    'default'     => '',
                    'desc_tip'    => false,
                ),
                'qrcode' => array(
                    'title'       => __( 'Upload QR Code', 'pagos-moviles-peru' ),
                    'type'        => 'file',
                    'description' => __( 'Upload the QR of your business here (must be in .jpg or .png image)', 'pagos-moviles-peru' ),
                    'accept'     => 'image/*',
                ),
                'needcapture' => array(
                    'title'       => __( 'Capture required', 'pagos-moviles-peru' ),
                    'label'       => __( 'Transaction screenshot required', 'pagos-moviles-peru' ),
                    'type'        => 'checkbox',
                    'description' => __( 'Check this if you want the screenshot required on the checkout process', 'pagos-moviles-peru' ),
                ),
                'description' => array(
                    'type'        => 'title',
                    'description' => '<b>'.__( 'Only upload the QR image, minimun dimension 200 x 200', 'pagos-moviles-peru' ) . '</b>',
                ),
            );
	 	}
 
        public function payment_fields()
        {
            global $wpdb;

            $data = $wpdb->get_row("SELECT qrcode FROM " . PAGO_MOVILES_PERU_DB_TABLE . " WHERE id = " . self::ID . " LIMIT 1");        
            echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';
        
            do_action( 'woocommerce_credit_card_form_start', $this->id );
            echo sprintf( wp_kses( '<div class="form-row form-row-wide">
                <img class="qrcode-image" src="%1$s" alt="" style="float: inherit; width: 10rem; height: 10rem; max-height: inherit; margin: 0px auto 20px auto;">
                <span class="text-bold">%3$s</span><br/>
                <span class="text-bold phone-number">%2$s</span>
                <label>'.__( 'Upload your payment capture', 'pagos-moviles-peru' ).' %4$s</label>
                <input id="pago_moviles_peru_trf_image" name="pago_moviles_peru_trf_image" type="file" onchange="prepareImage(this)" />
                </div>
                <div class="clear"></div>', array(
                    'div' => array(
                        'class' => array(),
                    ),
                    'br' => array(),
                    'a' => array(),
                    'img' => array(
                        'src' => array(),
                        'alt' => array(),
                        'style' => array(),
                        'class' => array(),
                    ),
                    'span' => array(
                        'class' => array(),
                    ),
                    'input' => array(
                        'id' => array(),
                        'name' => array(),
                        'type' => array(),
                        'onchange' => array(),
                    ),
                    'label' => array(),
                )), $data->qrcode, $this->phone, $this->instructions, $this->needcapture == 'yes' ? '<span class="required">*</span>' : '');
        
            do_action( 'woocommerce_credit_card_form_end', $this->id );        
            echo '<div class="clear"></div></fieldset>';
		}
 
        public function payment_scripts($force = false)
        {
            if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) && !$force ) {
                return;
            }
        
            if ( 'no' === $this->enabled ) {
                return;
            }

            wp_register_style('pago_moviles_peru_css', PAGO_MOVILES_PERU_ASSETS . 'style.css');
            wp_enqueue_style('pago_moviles_peru_css');
            
            wp_register_script('pago_moviles_peru_js', PAGO_MOVILES_PERU_ASSETS . 'script.js');
            wp_enqueue_script('pago_moviles_peru_js');
            
            wp_localize_script('pago_moviles_peru_js', 'ajax_var', array(
                'url'    => admin_url('admin-ajax.php'),
                'nonce'  => wp_create_nonce('pago_moviles_peru_yape_nonce'),
                'action' => 'upload_yape_capture'
            ));
        }
 
        public function validate_fields()
        {
            if( $this->needcapture == 'yes' && empty( $_POST['yape_img'] ) && $_POST['payment_method'] == 'pago_moviles_peru' ) {
                wc_add_notice( 'You must upload the YAPE transaction capture', 'error' );
                return false;
            }
            return true;
        }

        public function show_yape_field_order( $order )
        {  
            $order_id = $order->get_id();
            if ( get_post_meta( $order_id, '_yape_img', true ) ) {
                $this->payment_scripts(true);

                echo sprintf( wp_kses( '<div id="yapeModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <p><img src="%1$s" alt="yape_capture"/></p>
                    </div>
                </div>', array(
                    'div' => array(
                        'id' => array(),
                        'class' => array(),
                    ),
                    'span' => array(
                        'class' => array(),
                    ),
                    'p' => array(),
                    'img' => array(
                        'src' => array(),
                        'alt' => array(),
                    ),
                )), get_post_meta( $order_id, '_yape_img', true ) );

                echo sprintf( esc_html__( '%1$s%5$sYAPE Capture:%6$s%7$s%3$sview image%4$s %2$s', 'pagos-moviles-peru'),
                    '<p>', '</p>','<a href="#!" id="yapeBtn">', '</a>', '<strong>', '</strong>', '<br/>');
            }
        } 
        
        public function show_yape_field_emails( $order, $sent_to_admin, $plain_text, $email )
        {
            if ( get_post_meta( $order->get_id(), '_yape_img', true ) ) {
                $href = '<a target="_blank" href="' . get_post_meta( $order->get_id(), '_yape_img', true ) . '">';
                $message = sprintf(esc_html__('%1$s%5$sYAPE Capture:%6$s%7$s%3$sview image%4$s %2$s', 'pagos-moviles-peru'), '<p>', '</p>', $href, '</a>', '<strong>', '</strong>', '<br/>');
                echo $message;
            }
        }

        public function process_payment( $order_id )
        {
            global $woocommerce;

            $order = wc_get_order( $order_id );
         
            $order->payment_complete();
            $order->reduce_order_stock();

            if($_POST['payment_method'] == 'pago_moviles_peru') {
                $order->update_status( 'on-hold' );
        
                $order_note = __( 'Thanks for your order, we will shortly validate it', 'pagos-moviles-peru' );
                $order->add_order_note( $order_note, true );
               
                update_post_meta( $order_id, '_yape_img', esc_url( $_POST['yape_img'] ) );
            }
    
            $woocommerce->cart->empty_cart();
    
            return array(
                'result' => 'success',
                'redirect' => $this->get_return_url( $order )
            );
        }
        
        public function process_admin_options()
        {
            global $wpdb;
            parent::process_admin_options();

            $qrcode = $_FILES['woocommerce_pago_moviles_peru_qrcode'];
            
            if ( empty( $_POST['woocommerce_pago_moviles_peru_title'] ) ) {
                WC_Admin_Settings::add_error( __( 'Error: Please fill the payment title', 'pagos-moviles-peru' ) );
                return false;
            }

            if ( empty( $_POST['woocommerce_pago_moviles_peru_instructions'] ) ) {
                WC_Admin_Settings::add_error( __( 'Error: Please fill the payment instructions', 'pagos-moviles-peru' ) );
                return false;
            }
            
            if ( empty( $_POST['woocommerce_pago_moviles_peru_phone'] ) ) {
                WC_Admin_Settings::add_error( __( 'Error: Please fill the payment phone', 'pagos-moviles-peru' ) );
                return false;
            }

            if ( empty( $_FILES ) || empty( $qrcode['name'] ) ) {
                $data = $wpdb->get_row("SELECT qrcode FROM " . PAGO_MOVILES_PERU_DB_TABLE . " WHERE id = " . self::ID . " LIMIT 1");
                
                if ($data->qrcode) {
                    WC_Admin_Settings::add_message( __( 'No QR code submitted, the previous one will be used', 'pagos-moviles-peru' ) );
                } else {
                    WC_Admin_Settings::add_error( __( 'Error: Please add some QR code image', 'pagos-moviles-peru' ) );
                }

                return false;
            }
            
            $data = file_get_contents($qrcode['tmp_name']);
            $base64 = 'data:' . $qrcode['type'] . ';base64,' . base64_encode($data);
            
            $wpdb->delete(
                PAGO_MOVILES_PERU_DB_TABLE,
                array('id'=> self::ID)
            );
    
            $wpdb->insert(
                PAGO_MOVILES_PERU_DB_TABLE,
                array('id' => self::ID, 'qrcode' => $base64)
            );
        }

        private function sanitize(string $data)
        {
            return strip_tags( stripslashes( sanitize_text_field($data) ) );
        }

        private function convertImage($originalImage, $outputImage, $quality)
        {
            $exploded = explode('.', $originalImage);
            $ext = $exploded[count($exploded) - 1]; 

            if (preg_match('/jpg|jpeg|jfif/i',$ext))
                $imageTmp = imagecreatefromjpeg($originalImage);
            else if (preg_match('/png/i',$ext))
                $imageTmp = imagecreatefrompng($originalImage);
            else if (preg_match('/gif/i',$ext))
                $imageTmp = imagecreatefromgif($originalImage);
            else if (preg_match('/bmp/i',$ext))
                $imageTmp = imagecreatefrombmp($originalImage);
            else
                return 0;

            imagejpeg($imageTmp, $outputImage, $quality);
            imagedestroy($imageTmp);

            return 1;
        }
 	}
}