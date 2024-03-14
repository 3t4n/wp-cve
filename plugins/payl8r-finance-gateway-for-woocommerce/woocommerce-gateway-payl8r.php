<?php
/**
 * Plugin Name: PayL8r Gateway for WooCommerce
 * Plugin URI: https://payl8r.com/
 * Description: Take payments on your store using PayL8r.
 * Version: 1.5.5
 *
 * @package WC_PayL8r
 */

use Automattic\WooCommerce\Client;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Required minimums and constants.
 */
define( 'WC_PAYL8R_VERSION', '1.5.5' );
define( 'WC_PAYL8R_MIN_PHP_VER', '5.6.0' );
define( 'WC_PAYL8R_MIN_WC_VER', '2.6.0' );
define( 'WC_PAYL8R_MAIN_FILE', __FILE__ );

if ( ! class_exists( 'WC_PayL8r' ) ) :

	/**
	 * Plugin definition class.
	 */
	class WC_PayL8r {

		/**
		 * Singleton The reference the *Singleton* instance of this class.
		 *
		 * @var WC_PayL8r
		 */
		private static $instance;

		/**
		 * Returns the *Singleton* instance of this class.
		 *
		 * @return Singleton The *Singleton* instance.
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Private clone method to prevent cloning of the instance of the
		 * *Singleton* instance.
		 *
		 * @return void
		 */
		private function __clone() {}

		/**
		 * Public unserialize method to prevent unserializing of the *Singleton*
		 * instance.
		 *
		 * @return void
		 */
		public function __wakeup() {}

		/**
		 * Notices (array)
		 *
		 * @var array
		 */
		public $notices = array();

		/**
		 * Protected constructor to prevent creating a new instance of the
		 * *Singleton* via the `new` operator from outside of this class.
		 */
		protected function __construct() {
			add_action( 'admin_init', array( $this, 'check_environment' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );
			add_action( 'plugins_loaded', array( $this, 'init' ) );
		}

		/**
		 * Init the plugin after plugins_loaded so environment variables are set.
		 */
		public function init() {
			// Don't hook anything else in the plugin if we're in an incompatible environment.
			if ( self::get_environment_warning() ) {
				return;
			}

			// Init the gateway itself.
			$this->init_gateways();

			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

			add_action( 'woocommerce_receipt_wc_payl8r', array( $this, 'receipt_page' ) );
			add_action( 'woocommerce_api_wc_wc_payl8r', array( $this, 'ipn_response' ) );
            add_action( 'woocommerce_thankyou_wc_payl8r', array( $this, 'thankyou_page' ) );
            
            $this->init_calculator();
            $this->init_product_meta();
		}

		/**
		 * Allow this class and other classes to add slug keyed notices (to avoid duplication).
		 *
		 * @param string $slug Unique key for this message.
		 * @param string $class CSS class for the message.
		 * @param string $message Message content.
		 */
		public function add_admin_notice( $slug, $class, $message ) {
			$this->notices[ $slug ] = array(
				'class'   => $class,
				'message' => $message,
			);
		}


		/**
		 * The backup sanity check, in case the plugin is activated in a weird way,
		 * or the environment changes after activation.
		 */
		public function check_environment() {
			$environment_warning = self::get_environment_warning();

			if ( $environment_warning && is_plugin_active( plugin_basename( __FILE__ ) ) ) {
				$this->add_admin_notice( 'bad_environment', 'error', $environment_warning );
			}
		}

		/**
		 * Checks the environment for compatibility problems.  Returns a string with the first incompatibility
		 * found or false if the environment has no problems.
		 */
		static function get_environment_warning() {
			if ( version_compare( phpversion(), WC_PAYL8R_MIN_PHP_VER, '<' ) ) {
				$message = __( 'WooCommerce PayL8r - The minimum PHP version required for this plugin is %1$s. You are running %2$s.', 'woocommerce-gateway-payl8r' );

				return sprintf( $message, WC_PAYL8R_MIN_PHP_VER, phpversion() );
			}

			if ( ! defined( 'WC_VERSION' ) ) {
				return __( 'WooCommerce PayL8r requires WooCommerce to be activated to work.', 'woocommerce-gateway-payl8r' );
			}

			if ( version_compare( WC_VERSION, WC_PAYL8R_MIN_WC_VER, '<' ) ) {
				$message = __( 'WooCommerce PayL8r - The minimum WooCommerce version required for this plugin is %1$s. You are running %2$s.', 'woocommerce-gateway-payl8r' );

				return sprintf( $message, WC_PAYL8R_MIN_WC_VER, WC_VERSION );
			}

			return false;
		}

		/**
		 * Adds plugin action links.
		 *
		 * @param array $links Existing plugin action links.
		 */
		public function plugin_action_links( $links ) {
			$setting_link = $this->get_setting_link();

			$plugin_links = array(
				'<a href="' . $setting_link . '">' . __( 'Settings', 'woocommerce-gateway-payl8r' ) . '</a>',
			);
			return array_merge( $plugin_links, $links );
		}

		/**
		 * Get setting link.
		 *
		 * @return string Setting link
		 */
		public function get_setting_link() {
			return admin_url( 'admin.php?page=wc-settings&tab=checkout&section=payl8r' );
		}

		/**
		 * Display any notices collected.
		 */
		public function admin_notices() {
			foreach ( (array) $this->notices as $notice_key => $notice ) {
				echo "<div class='" . esc_attr( $notice['class'] ) . "'><p>";
				echo wp_kses( $notice['message'], array( 'a' => array( 'href' => array() ) ) );
				echo '</p></div>';
			}
		}

		/**
		 * Initialize the gateway. Called very early - in the context of the plugins_loaded action.
		 */
		public function init_gateways() {
			if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
				return;
			}

			include_once( dirname( __FILE__ ) . '/includes/class-wc-gateway-payl8r.php' );

            add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateways' ) );
        }

		/**
		 * Add the gateways to WooCommerce.
		 *
		 * @param array $methods Existing payment methods.
		 */
		public function add_gateways( $methods ) {
			$methods[] = 'WC_Gateway_PayL8r';

			return $methods;
        }
        
        /**
         * Initialize the calculator script
         */
        public function init_calculator() {
            add_filter('woocommerce_after_add_to_cart_form', array( $this, 'add_calculator'));
        }

        public function add_calculator() {
            global $product;
            if(empty($product)) {
                return;
            }

            $payl8r_settings = new WC_Gateway_PayL8r();
            $payl8r_username = $payl8r_settings->username;

            $settings = get_option('woocommerce_payl8r_settings');

            // If plugin is disabled, abandon
            $plugin_disabled = $settings['enabled'] == 'no';
            if($plugin_disabled) {
                return;
            }

            // Gets the prices of the variables
            if($product->is_type('variable')){
                $handle=new WC_Product_Variable($product->id);
                $variations=$handle->get_children();
                $variationPrices =[];
                foreach ($variations as $value) {
                    $single_variation=new WC_Product_Variation($value);
                    $variationPrices[$value] =$single_variation->price;
                }
                $variationPrices = json_encode($variationPrices);
            }

            if($product->get_price()>=50){
                $displayCalculator = true;
            } else {
                $displayCalculator = false;
            }

            ob_start();
			?>
            <div id="pl-calculator-light-app" style="<?= $displayCalculator ? 'display:block;' : 'display:none;' ?>"></div>
            <p id="pl-calculator-too-low" style="<?= $displayCalculator ? 'display:none;' : 'display:block;' ?>">To use <img src="https://assets.payl8r.com/images/payl8r.svg" alt="Payl8r" style="max-width:95px;height:auto;" />, please spend more than £50</p>
			<script>
                var setPrice = <?= $product->get_price(); ?>;
                <?php if($product->is_type('variable')): ?>
                var variationPrices = <?= $variationPrices ?>;
                jQuery( 'input.variation_id' ).change( function(){
                    if( '' != jQuery(this).val() ) {
                        var var_id = jQuery(this).val();
                        setPrice = variationPrices[var_id];

                        document.plCalcPrice = {Price: setPrice};
                        if(setPrice < 50){
                            jQuery('#pl-calculator-light-app').css('display', 'none');
                            jQuery('#pl-calculator-too-low').css('display', 'block');
                        } else {
                            jQuery('#pl-calculator-light-app').css('display', 'block');
                            jQuery('#pl-calculator-too-low').css('display', 'none');
                        }
                    }
                });
                <?php endif; ?>
			// sets the price, must be set prior to the app script //
				document.plCalcPrice = {Price: setPrice};
            // sets username, does not need to be set prior to the app script //
                const username = '<?php echo $payl8r_username; ?>';
                document.plGetUsername = {Username: username};
			</script>
			<script
					src="https://assets.payl8r.com/js/pl-calculator-light-app.js"
					id="pl-calculator-light-app-script"
            >
			</script>
            <?php
            echo ob_get_clean();
        }

        
        public function init_product_meta() {
            add_filter('woocommerce_after_main_content', array( $this, 'add_init_product_meta'));
        }

        public function add_init_product_meta() {
            global $product;
            if(empty($product)) {
                return;
            }
			?>
				<script>
					function defer(method) {
						if (document.plCalcPrice) {
							method();
						} else {
							setTimeout(function() { defer(method) }, 50);
						}
					}
					defer(function(){
						document.plCalcPrice.rerender("<?php echo $product->get_price(); ?>");
						console.log('Updated new calculator value');
					});
				</script>
            <?php
        }
	}

	$GLOBALS['wc_payl8r'] = WC_PayL8r::get_instance();
endif;
