<?php
/*
Plugin Name: WooCommerce Fattureincloud
Plugin URI:  https://woofatture.com/
Description: WooCommerce Fattureincloud integration
Version:     2.6.3
Requires at least: 5.0
Requires PHP: 7.4
Author:      Woofatture
Author URI:  https://woofatture.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: woo-fattureincloud
Domain Path: /languages
Contributors: wpnetworkit, woofatture, cristianozanca
WC requires at least: 7.0
WC tested up to: 8.3
 */

if (!defined('ABSPATH')) {

    exit; // Exit if accessed directly

}


function woo_fattureincloud_textdomain() 
{

        load_plugin_textdomain('woo-fattureincloud', false, basename(dirname(__FILE__)) . '/languages');
    
}

#######################################################################################



if (class_exists('woo_fattureincloud_premium')) {

    wp_die('disabilitare <b>WooCommerce Fattureincloud Premium</b> <button onclick="history.back()">Indietro</button>');

    }

#############################################################################################



    add_action('plugins_loaded', 'woo_fattureincloud_textdomain');


if (!class_exists('woo_fattureincloud')) : {
        
    class woo_fattureincloud
    {

        public function __construct()
        {


                add_action('admin_notices', array ( $this, 'check_wc_cf_piva'));
                
                include_once plugin_dir_path(__FILE__) . 'inc/menu_setup.php';

                include_once plugin_dir_path(__FILE__) . 'inc/setup_page_display.php';

                include_once plugin_dir_path( __FILE__ ) . 'inc/notice_recensione.php';

                
                add_option('fattureincloud_paid', '1');

                add_option('fattureincloud_send_choice', 'fattura');

                add_option('fattureincloud_auto_save', 'nulla');

                add_action('admin_enqueue_scripts', array($this, 'register_woo_fattureincloud_styles_and_scripts'));

                add_action('admin_menu', 'woo_fattureincloud_setup_menu');

                add_action('admin_menu', 'add_wfic_to_woocommerce_navigation_bar');

                add_action('woocommerce_order_status_completed', array(&$this, 'fattureincloud_order_completed'), 10, 1);

                add_action('wp_footer', array($this, 'woo_fattureincloud_enqueue_script'));

                add_action('admin_notices', array($this, 'woo_fic_admin_notices'));

                add_action( 'before_woocommerce_init', function() {
                    if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
                        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
                    }
                } );


                



                
            if (1 == get_option('fattureincloud_partiva_codfisc') ) {

                    include_once plugin_dir_path(__FILE__) . 'inc/vat_number.php';

                    add_filter('woocommerce_admin_billing_fields',  'admin_billing_field');

                    if ( ! is_admin() ) {
                    add_filter('woocommerce_billing_fields', 'billing_fields_woofc', 10, 1);
                    }

                    //add_action('woocommerce_thankyou', 'woo_fic_displayfield_typ', 10, 1);

                    add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta', 10, 1);

                    //add_action( 'woocommerce_checkout_update_customer', 'save_account_cod_fisc_field', 10, 2 );

                    if (1 == get_option('activate_customer_receipt') ) {

                    add_action('woocommerce_checkout_before_customer_details', 'billing_ricevuta_wc_custom_checkout_field', 10);

                    }

            }

        }

        
        function woo_fic_admin_notices()
        {
            if (!is_plugin_active('woocommerce/woocommerce.php')) {
                echo "<div class='notice error is-dismissible'><p>".__(
                    'To use the plug-in <b>WooCommerce Fattureincloud</b> the
                     <b>WooCommerce</b> plug-in must be installed and activated!', 'woo-fattureincloud'
                )."</div>";
            }
        }


        function fattureincloud_order_completed($order_id) 
        {

            if ('fattura' == get_option('fattureincloud_auto_save')) {
                    
                    //error_log("$order_id fattura set to COMPLETED", 0);

                    $invoice_elet_type_wfic= false;

                    update_option('woo_fattureincloud_order_id', $order_id);

                    include plugin_dir_path(__FILE__) . 'inc/setup-file.php';
                    include plugin_dir_path(__FILE__) . 'inc/send_to_fattureincloud.php';

                    if (!empty($response_value ['error'] )) {

                        update_option('fattureincloud_autosent_id_fallito', $order_id);

                        error_log("fattura dall'ordine".$order_id ."NON creata =>".$response_value ['error']);

                    } elseif (empty($response_value ['error'] )) {

                        update_option('fattureincloud_autosent_id_successo', $order_id);

                        error_log("fattura dall'ordine ".$order_id ." creata con successo");

                    }

            }

        }


        public function check_wc_cf_piva() 
        {

            if (1 == get_option('fattureincloud_partiva_codfisc')) {

                if (in_array('woo-piva-codice-fiscale-e-fattura-pdf-per-italia/dot4all-wc-cf-piva.php', apply_filters('active_plugins', get_option('active_plugins')))) {


                        deactivate_plugins(plugin_basename('woo-piva-codice-fiscale-e-fattura-pdf-per-italia/dot4all-wc-cf-piva.php'));

                        $class = "error";
                        $message = sprintf(
                            __('L\'attivazione dell\'opzione <b>WooCommerce Fatteincloud campi CF PI PEC CD</b> a causato la <b>disattivazione</b> del plugin %sWooCommerce P.IVA e Codice Fiscale per Italia%s!', 'woo-fattureincloud'),
                            '<a href="https://it.wordpress.org/plugins/woo-piva-codice-fiscale-e-fattura-pdf-per-italia/">', '</a>'
                        );

                        echo"<div class=\"$class\"> <p>$message</p></div>";

                }
            }
        }



        function woo_fattureincloud_enqueue_script() 
        {

            if (is_checkout()) {
                
                    wp_enqueue_script('woo_fic_cf', plugins_url('assets/js/woo_fic_cf.js', __FILE__, array('jquery'), 1.0, true));

            }

                          
        }



            /*Custom stylesheet to load image and js scripts only on backend page */

            
            
        function register_woo_fattureincloud_styles_and_scripts($hook)
        {


                $current_screen = get_current_screen();

            if (strpos($current_screen->base, 'woo-fattureincloud') === false) {
                    return;
                
            } else {

                    wp_enqueue_style('boot_css', plugins_url('assets/css/woo_fattureincloud.css', __FILE__));
                                    // Load the datepicker jQuery-ui plugin script

                    wp_enqueue_script('jquery-ui-datepicker');

                    wp_enqueue_script(
                        'wp-jquery-date-picker', plugins_url(
                            'assets/js/woo_admin.js', __FILE__, array(
                                'jquery', 'jquery-ui-core'), time(), true
                        )
                    );
  
                    /*wp_enqueue_style( 'jquery-ui-datepicker' ); */

                    wp_register_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
                    wp_enqueue_style('jquery-ui'); 
          
            }



        }

    }
        
    }

        /*Creates a new instance*/
        new woo_fattureincloud;

endif;


