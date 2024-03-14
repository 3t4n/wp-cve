<?php
if (!defined('ABSPATH'))
    exit;

class ATR_random_sku_for_Woocommerce {

    /**
     * The single instance of ATR_random_sku_for_Woocommerce.
     * @var 	object
     * @access  private
     * @since 	1.0.0
     */
    private static $_instance = null;

    /**
     * Settings class object
     * @var     object
     * @access  public
     * @since   1.0.0
     */
    public $settings = null;

    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_version;

    /**
     * The token.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_token;

    /**
     * The main plugin file.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $file;

    /**
     * The main plugin directory.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $dir;

    /**
     * The plugin assets directory.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_dir;

    /**
     * The plugin assets URL.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_url;

    /**
     * Suffix for Javascripts.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $script_suffix;

    /**
     * random_sku.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $random_sku;

    /**
     * Constructor function.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function __construct($file = '', $version = '1.0.0') {

        $this->_version = $version;
        $this->_token = 'atr_random_sku_for_woocommerce';

        // Load plugin environment variables
        $this->file = $file;
        $this->dir = dirname($this->file);
        $this->assets_dir = trailingslashit($this->dir) . 'assets';
        $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $this->file)));

        $this->script_suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        register_activation_hook($this->file, array($this, 'install'));

        // Load admin JS & CSS
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 10, 1);
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_styles'), 10, 1);
        // Load API for generic admin functions
        if (is_admin()) {
            $this->admin = new ATR_random_sku_for_Woocommerce_Admin_API();
        }

        // Handle localisation
        $this->load_plugin_textdomain();
        add_action('init', array($this, 'load_localisation'), 0);

        // Add auto sku button to product edit page
        add_action('woocommerce_product_options_inventory_product_data', array($this, 'woo_add_custom_general_fields')); // since v 1.0.2

        // **** Check if the suggested sku exist in DB *****
        add_action('admin_footer', array($this, 'atr_check_sku_action_javascript'));
        add_action('wp_ajax_atr_check_sku_action', array($this, 'atr_check_sku_callback'));
		
		add_action('save_post', array($this, 'save_latest_next_highest_sku'), 10, 3);
		
    }

// End __construct ()

/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID of the product.
 */
function save_latest_next_highest_sku( $post_id ) {

	if (get_option('atr_select_sku_format') === 'increment') {
		/*
		 * Save the last sku + 1 to atr_incremental_sku_start options if it is the highest
		 */
		if ( isset( $_POST['_sku'] ) ) {		
			$posted_sku = sanitize_text_field( $_POST['_sku'] );
			$prefix = get_option('atr_prefix_sku');
			$str = $posted_sku;

			if (substr($str, 0, strlen($prefix)) == $prefix) {
				$str = substr($str, strlen($prefix));
			}		
			$next_highest_sku = $str + 1;
			if (get_option('atr_incremental_sku_start')){
				if ($next_highest_sku > get_option('atr_incremental_sku_start') ){
					update_option( 'atr_incremental_sku_start', ($next_highest_sku) );
				}			
			}
			else {
				update_option( 'atr_incremental_sku_start', ($next_highest_sku) );
			}

			
			
		}		
	}

}



    // Add auto sku button to product edit page
    public function woo_add_custom_general_fields() {

        global $woocommerce, $post;

        echo '<div class="options_group">';
        ?>

        <table >
            <tr>
                <th rowspan="2"><input id="auto-sku" type="button" class="button" value="<?php echo __('Auto SKU') ?>" /></th>
                <td><input id="test_sku0" type="radio" value="0" name="test_sku" /><?php echo __('Generate random SKU') ?>&nbsp;<input type="checkbox" name="overwrite" class="overwrite" value="no"><?php echo __('Overwrite SKU textbox') ?><br /></td>
            </tr>
            <tr>
                <td><input id="test_sku1" type="radio" value="1" name="test_sku" checked /><?php echo __('Just check current SKU') ?></td>
            </tr>
        </table>
        <p class="auto_sku_message"><?php echo __('Select option and click the button.<br />"random" will replace the sku with random one and will check it.<br /> "just check" will check the sku without replacing it in the textbox.') ?></p>
        <?php
        echo '</div>';
    }

    // **** Check if the suggested sku exist in DB *****
    function atr_check_sku_action_javascript() {
        ?>
        <script type="text/javascript" >
            jQuery(document).ready(function ($) {
                // onload
                if ($("#test_sku0").attr("checked"))
                    jQuery('#auto-sku').prop('value', 'Auto SKU');
                else
                    jQuery('#auto-sku').prop('value', 'Check SKU ');
                // On radio change
                $("input[name=test_sku]:radio").change(function () {
                    if ($("#test_sku0").attr("checked"))
                        jQuery('#auto-sku').prop('value', 'Auto SKU');
                    else
                        jQuery('#auto-sku').prop('value', 'Check SKU ');
                })
        <?php
        /**
         * If user selected to auto fill on empty SKU
         */
        if (get_option('atr_force_sku_on_empty')) { // User selected to auto fill SKU for new products (or products with empty SKU) 
            ?>
                    // Automatically adds new random SKU to new product when add new product page loads
					if (jQuery("#_sku").length) {
                        var random_id_check = '';
                        if (!jQuery('#_sku').val().length > 0) {
                            //alert('Set new SKU to empty selected!');                                  
                            random_id_check = <?php echo $this->atr_generate_random_sku(); ?>;
                            // Start testing the SKU with the DB
                            var data = {
                                'action': 'atr_check_sku_action',
                                'sku_to_pass': random_id_check
                            };
                            test_sku_exist(data, random_id_check);
                        }

                    }

        <?php } ?>

                // Click Event - The random SKU button
                jQuery('#auto-sku').click(function (event) {
                    event.preventDefault();
                    var random_id_check;
                    var test_skuValue = jQuery("input[name='test_sku']:checked").val();
					<?php if (!get_option('atr_select_sku_format')) { // SKU format not selected in settings         ?>
									alert('SKU format not selected! You must go to main Settings -> ATR rand sku Woo and save your options first!');
									return;
					<?php } ?>
                    if ((!jQuery('#_sku').val().length > 0) && (test_skuValue === '1')) { // User selected to test SKU                    
                        alert('SKU textbox is empty!'); // No SKU to test
                    } else {
                        if (test_skuValue === '0') { // User selected to create new SKU
                            random_id_check = <?php echo $this->atr_generate_random_sku(); ?>;
                        } else {
                            random_id_check = jQuery('#_sku').val(); // Take sku to test from SKU textbox
                        }
                        // Start testing the SKU with the DB
                        var data = {
                            'action': 'atr_check_sku_action',
                            'sku_to_pass': random_id_check
                        };
                        test_sku_exist(data, random_id_check);
                    }
                });
            });

            /**
             * Test if SKU exist in DB
             * @access  public
             * @since   1.0.1
             */

            function test_sku_exist(data, random_id_check) {
                jQuery.post(ajaxurl, data, function (response) {
                    if (response === '0') { // select count = 0 no much sku found in db
                        if (jQuery('#_sku').val().length > 0) {
                            if (jQuery('.overwrite').prop("checked") === true) {
                                jQuery('.auto_sku_message').html('<span style="color:blue;font-weight:bold;">' + random_id_check + '</span> not exists! Pasted to sku field.');
                                jQuery('#_sku').val(random_id_check);
                            } else {
                                jQuery('.auto_sku_message').html('<span style="color:blue;font-weight:bold;">' + random_id_check + '</span> not exists! You can copy paste it. ');
                            }
                        } else {
                            jQuery('#_sku').val(random_id_check);
                            jQuery('.auto_sku_message').html(random_id_check + 'not exists! Pasted to sku field.');
                            var sku_label = jQuery('label[for="_sku"]').text();
                            var sku_not_saved_label = '<span style="color:red;font-weight:bold;">Auto! not saved yet!</span>';
                            jQuery('#_sku').after('<span style="color:red;font-weight:bold;">Auto! not saved yet!</span>');
                        }
                        jQuery('.auto_sku_message').css('color', 'green');
                    } else {
                        jQuery('.auto_sku_message').html('<span style="color:blue;font-weight:bold;">' + random_id_check + '</span> already exists! Found ' + response + ' products with this SKU');
                        jQuery('.auto_sku_message').css('color', 'red');
                    }
                });
            }
            // generate random sku and write it in the sku textbox
            function randomNumberFromRange(min, max, sku_prefix)
            {
                var text = "";
				text += sku_prefix;				
                var randomNumber = Math.floor(Math.random() * (max - min + 1) + min);
				text += randomNumber;
                return text;
            }
			// var possible = $sku_characters = get_option('atr_characters_for_SKU')
			// var sku_length = $sku_length = get_option('atr_sku_length');
            function makeid(possible, sku_length, sku_prefix)
            {
                var text = "";
				text += sku_prefix;
                //var possible = "abcdefghijklmnopqrstuvwxyz0123456789";
                for (var i = 0; i < sku_length; i++)
                    text += possible.charAt(Math.floor(Math.random() * possible.length));
                return text;

	// var chars = possible;
	// var string_length = sku_length;
	// var randomstring = '';
	// for (var i=0; i<string_length; i++) {
		// var rnum = Math.floor(Math.random() * chars.length);
		// randomstring += chars.substring(rnum,rnum+1);
	// }
	// return randomstring;
			
            }
        </script> 
        <?php
    }

	
	
	
    /**
     * Generate the SKU according to option selected
     * @access  public
     * @since   2.0.0
     * @return  return $random_sku;
     */	
    function atr_generate_random_sku() {
		$random_sku = '';
		// Check what SKU format selected in settings
		// and generate the SKU into $random_sku
		if (get_option('atr_select_sku_format') === 'charactersforsku') { // SKU format selected as string of characters
		$random_sku = $this->generate_sku_from_characters();
		} 
		elseif (get_option('atr_select_sku_format') === 'maxminsku') {
		$random_sku = $this->generate_sku_from_min_max();	
		}
		elseif (get_option('atr_select_sku_format') === 'increment') {
		$random_sku = $this->generate_incremental_sku();
		}
		else {// SKU format not selected in settings
		?>
			alert('You must go to main Settings -> ATR rand sku Woo and save your options first!');
		<?php
		}
		return $random_sku; // Set JS var random_id_check to the generated number
    }


    function atr_check_sku_callback() {
        global $wpdb;
        $sku = strval($_POST['sku_to_pass']);
        //$product_id = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value= %s LIMIT 1", $sku));
        $product_id = $wpdb->get_var($wpdb->prepare("SELECT count(meta_value) FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value= %s LIMIT 1", $sku));
        wp_reset_query();
        echo $product_id;
        wp_die(); // this is required to terminate immediately and return a proper response
    }

	
    /**
     * Check if there is a definition for SKU prefix
     * @access  public
     * @since   2.0.0
     * @return  get_option('atr_prefix_sku')
     */	
    public function check_sku_prefix() {
		if (get_option('atr_prefix_sku')) {
			return get_option('atr_prefix_sku');
		}
		else {
			return '';
		}
	}	
	
    /**
     * Generate SKU from string of characters
	 * Added $sku_prefix since 2.0.0
     * @access  public
     * @since   1.0.1
     * @return  $random_sku_characters
     */
    public function generate_sku_from_characters() {
        $random_sku_characters = '';
		$sku_prefix = $this->check_sku_prefix();
        if ((get_option('atr_sku_length') != '') && (get_option('atr_characters_for_SKU') != '')) { // comment added on v 1.0.2 - check if sku lemgth option is defined AND the string of charecters is both not empty
            $sku_characters = get_option('atr_characters_for_SKU');
            $sku_length = get_option('atr_sku_length');
			
			//$sku_prefix = 'ffffffff';
            $random_sku_characters = 'makeid("' . $sku_characters . '",' . $sku_length . ',"' . $sku_prefix . '")';
        } else {
			if ((get_option('atr_sku_length') === '') && (get_option('atr_characters_for_SKU') != '')){ // condition added on v 1.0.2 - sku lemgth is empty AND the string of charecters is defined
				$sku_characters = get_option('atr_characters_for_SKU');
				$sku_length = 8;
				$random_sku_characters = 'makeid("' . $sku_characters . '",' . $sku_length . ',"' . $sku_prefix . '")';				
			}
			else {
				$random_sku_characters = 'makeid("abcdefghijklmnopqrstuvwxyz0123456789", 8,"' . $sku_prefix . '")'; // condition changed on v 1.0.2
			}
            
        }
        return $random_sku_characters;
    }

    /**
     * Generate SKU from min max range
     * @access  public
     * @since   1.0.1
     * @return  $random_sku_min_max
     */
    public function generate_sku_from_min_max() {
        $random_sku_min_max = '';
		$sku_prefix = $this->check_sku_prefix();
        if ((get_option('atr_min_number_for_number') != '') && (get_option('atr_max_number_for_number') != '')) {
            $min_num = get_option('atr_min_number_for_number');
            $max_num = get_option('atr_max_number_for_number');
            $random_sku_min_max = 'randomNumberFromRange(' . $min_num . ',' . $max_num . ',"' . $sku_prefix . '")';
        } else {
            $random_sku_min_max = 'randomNumberFromRange(100000000, 999999999,"' . $sku_prefix . '")';
        }
        return $random_sku_min_max;
    }
	
    /**
     * Generate incremental SKU 
     * @access  public
     * @since   1.0.1
     * @return  $incremental_sku
     */
    public function generate_incremental_sku() {
        $incremental_sku = '';
		$sku_prefix = $this->check_sku_prefix();
		$atr_incremental_sku_start = get_option('atr_incremental_sku_start');
		$atr_incremental_sku_min_num_digits = get_option('atr_incremental_sku_min_num_digits');
		
		if ($atr_incremental_sku_start) {
			$incremental_sku = $atr_incremental_sku_start; // Get the definition for last (or start) SKU
		}
		else{
			$incremental_sku = 1;
		}
		// Add padding 0 (zeros)  at SKU begining
		if ($atr_incremental_sku_min_num_digits) {
			$incremental_sku_length = 0;
			strlen($incremental_sku) > 0 ? $incremental_sku_length = strlen($incremental_sku) : $incremental_sku_length = 0;
			$incremental_sku = str_pad($incremental_sku, $atr_incremental_sku_min_num_digits, "0", STR_PAD_LEFT);
		}				
		if ($sku_prefix){
			$incremental_sku = "'" . $sku_prefix  . $incremental_sku . "'";
		}
		else {
			$incremental_sku = "'" . $incremental_sku . "'";
		}
        return $incremental_sku;
    }	

    /**
     * Load admin CSS.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function admin_enqueue_styles($hook = '') {
        wp_register_style($this->_token . '-admin', esc_url($this->assets_url) . 'css/admin.css', array(), $this->_version);
        wp_enqueue_style($this->_token . '-admin');
    }

// End admin_enqueue_styles ()

    /**
     * Load admin Javascript.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function admin_enqueue_scripts($hook = '') {
        wp_register_script($this->_token . '-admin', esc_url($this->assets_url) . 'js/admin' . $this->script_suffix . '.js', array('jquery'), $this->_version);

        wp_enqueue_script($this->_token . '-admin');
    }

// End admin_enqueue_scripts ()
    /**
     * Load plugin localisation
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function load_localisation() {
        load_plugin_textdomain('atr-random-sku-for-woocommerce', false, dirname(plugin_basename($this->file)) . '/lang/');
    }

// End load_localisation ()

    /**
     * Load plugin textdomain
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function load_plugin_textdomain() {
        $domain = 'atr-random-sku-for-woocommerce';

        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, false, dirname(plugin_basename($this->file)) . '/lang/');
    }

// End load_plugin_textdomain ()

    /**
     * Main ATR_random_sku_for_Woocommerce Instance
     *
     * Ensures only one instance of ATR_random_sku_for_Woocommerce is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see ATR_random_sku_for_Woocommerce()
     * @return Main ATR_random_sku_for_Woocommerce instance
     */
    public static function instance($file = '', $version = '1.0.0') {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($file, $version);
        }
        return self::$_instance;
    }

// End instance ()

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone() {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }

// End __clone ()

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup() {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }

// End __wakeup ()

    /**
     * Installation. Runs on activation.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function install() {
        $this->_log_version_number();
    }

// End install ()

    /**
     * Log the plugin version number.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    private function _log_version_number() {
        update_option($this->_token . '_version', $this->_version);
    }

// End _log_version_number ()
}
