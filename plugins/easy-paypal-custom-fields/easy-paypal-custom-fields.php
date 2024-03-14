<?php
/*
Plugin Name: Easy PayPal Custom Fields
Plugin URI: http://richardsweeney.com/blog/easy-paypal-custom-fields/
Description: This plugin uses custom fields to make creating a PayPal button super-easy. There is no complicated shortcut syntax to remember.
Version: 2.0.8
Author: Richard Sweeney
Author URI: http://richardsweeney.com/
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/



/*
 @richardsweeney
*/


class RPS_eppcf {
	
	// Array of currency codes
	private $currency_array = array(
		'Australian Dollar' => 'AUD',
		'Canadian Dollar' => 'CAD',
		'Czech Koruna'  => 'CZK',
		'Danish Krone' => 'DKK',
		'Euro' => 'EUR',
		'Hungarian Forint' => 'HUF',
		'Japanese Yen' => 'JPY',
		'Norwegian Krone' => 'NOK',
		'New Zealand Dollar' => 'NZD',
		'Polish Zloty' => 'PLN',
		'Pound Sterling' => 'GBP',
		'Singapore Dollar' => 'SGD',
		'Swedish Krona' => 'SEK',
		'Swiss Franc' => 'CHF',
		'U.S. Dollar' => 'USD'
	);
	
	// Types of button supported
	private $button_type_array = array(
		'Buy Now',
		'Donations'
	);
	
	// Available button themes
	private $button_theme_array = array(
		'light' => 'light theme',
		'dark' => 'dark theme',
		'blue' => 'blue theme',
		'red' => 'red theme',
		'pp_small' => 'PayPal image: small',
		'pp_large' => 'PayPal image: large'
	);

	// used for checking against individual post meta - see get_the_post_meta()
	private $some_meta = array(
		'amount',
		'postage',
		'item_no',
		'textfield_checkbox',
		'textfield_title',
		'quantity_checkbox',
		'require_address'
		//'drop_down_title'
	);
	
	// Default values - used for checking against individual post meta - see get_the_post_meta()
	private $default_meta = array(
		'user_email',
		'button_text',
		'button_type',
		'theme',
		'url',
		'currency'
	);
	
	// These variables are declared later
	public $opts; // default options, declared in __construct()
	public $my_post_meta; // indivdual, additional post meta, declared in get_the_post_meta()
	public $paypal_button; // the button, declared in create_button()

	
/**
	* All WordPress hooks must be added to constuctor function.
	*/
	public function __construct() {

		// Define current version
		define( 'PAYPAL_VERSION', '2.0.7' );
	
		// Activation Hook
		register_activation_hook( __FILE__, array( &$this, 'activate_my_plugin' ) );
	
		// Add stylesheet to the admin pages & the front end
		add_action( 'admin_print_styles-post.php', array( &$this, 'add_css' ) );
		add_action( 'admin_print_styles-post-new.php', array( &$this, 'add_css' ) );
		add_action( 'wp_print_styles', array( &$this, 'add_css' ) );
		
		// Add my JS to WP Admin
		add_action( 'init', array( &$this, 'add_my_js' ) );
		
		// Create options page & menu item
		add_action( 'admin_menu', array( &$this, 'add_my_options_page' ) );	
		
		// Create link to settings page on plugin activation
		add_filter( 'plugin_action_links', array( &$this, 'add_link_to_settings_page' ), 10, 2 );
		
		// Register and define the settings
		add_action( 'admin_init', array( &$this, 'create_settings_sections' ) );

		// Create paypal meta box
		add_action( 'add_meta_boxes', array( &$this, 'register_meta_box' ) );
	
		// Save post meta
		add_action('save_post', array( &$this, 'save_eppcf_meta' ) );
		
		// Hook into the_content & show the button baby
		add_action('the_content', array( &$this, 'display_button' ) );

		// Create a shortcode option
		add_shortcode( 'rps-paypal', array( &$this, 'create_shortcode' ) );

		$this->opts = get_option( 'rps_eppcf_options' );
		
	}
	
	
	/**
	* When the plugin is activated, add the default options
	*/
	public function activate_my_plugin() {
	
		/* On plugin activation register the following options */
		$button = array(
			'user_email' => '',
			'currency' => 'EUR',
			'button_text' => 'Checkout with Paypal',
			'button_type' => 'Buy now',
			'theme' => 'light theme',
			'url' => ''
		);
		if( !get_option( 'rps_eppcf_options' ) ) {
		
			update_option( 'rps_eppcf_options', $button );
		
		}
		
		/* Uninstall function */
		register_uninstall_hook( __FILE__, array( &$this, 'uninstall_my_plugin' ) );
	
	}
	
		
 /**
	* Uninstall function
	*/
	public function uninstall_my_plugin() {
			
		// Get any custom post types
		$args = array(
	  	'public'   => true,
		  '_builtin' => false
		); 
		$output = 'names';
		$operator = 'and';
		$custom_post_types = get_post_types( $args, $output, $operator );
		
		// Regular 'post' and 'page' post types
		$regular_post_types = array(
			'post' => 'post',
			'page' => 'page'
		);
		
		if( isset( $custom_post_types ) ) {
			// Merge the array to get ALL post types
			$all_post_types = array_merge( $regular_post_types, $custom_post_types );
		} else {
			$all_post_types = $regular_post_types;
		}
		
		//remove any additional post_meta
		foreach( $all_post_types as $this_post_type ) {
			$allposts = get_posts( 'numberposts=-1&post_type=' . $this_post_type . '&post_status=any' );
		}
	  foreach( $allposts as $everypost ) {
	    delete_post_meta( $everypost->ID, '_rps_eppcf_array' );
	  }
	  
	  // Delete any stored options
		delete_option( 'rps_eppcf_options' );
	  
	}
	
		
 /**
	* Add custom CSS
	*/
	public function add_css() {
		wp_enqueue_style( 'eppcf_css', plugin_dir_url( __FILE__ ) . 'css/paypal.css' );
	}
	
 /**
	* Add custom JavaScript
	*/
	public function add_my_js() {	
		//wp_deregister_script( 'jquery' );
		//wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js' );
		if( is_admin() ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'eppcf_js', plugin_dir_url( __FILE__ ) . 'js/paypal.jquery.js', 'jquery' );
		}
	}
	
	public function add_admin_js(){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'eppcf_admin_js', plugin_dir_url( __FILE__ ) . 'js/paypal.admin.js', 'jquery' );
	}
	
		
/**
	* Loops through post meta and stores it in @var $this->my_post_meta
	* Compares default values to values set per post
	* Per post values overwrite defaults
	*
	* @param array plugin's post-meta
	*/
	
	public function get_the_post_meta( $meta_array ){
	
		$post_meta_array = array();
		$post_meta_array['show'] = ( !empty( $meta_array['show'] ) ) ? $meta_array['show'] : 'no';
		$post_meta_array['name'] = ( !empty( $meta_array['name'] ) ) ? $meta_array['name'] : '';
		
		foreach( $this->some_meta as $meta ){
			$post_meta_array[$meta] = ( isset ( $meta_array[$meta] ) ) ? $meta_array[$meta] : '';
		}
		
		foreach( $this->default_meta as $default ){
			$post_meta_array[$default] = ( isset( $meta_array[$default] ) && !empty( $meta_array[$default] ) ) ? $meta_array[$default] : $this->opts[$default];
		}
		
		
		/* Backwards compatibility */
		// My old, stupid names for stuff, because I'm a dork.
		if( isset( $meta_array['username'] ) ) {
			$post_meta_array['user_email'] = $meta_array['username'];
		}
		if( isset( $meta_array['buttontext'] ) ) {
			$post_meta_array['button_text'] = $meta_array['buttontext'];
		}
		if( isset( $meta_array['button-type'] ) ) {
			$post_meta_array['button_type'] = $meta_array['button-type'];
		}
		if( isset( $meta_array['post-type'] ) ) {
			$post_meta_array['post_type'] = $meta_array['post-type'];
		}
		$this->my_post_meta = $post_meta_array;
	}
	
	
 /**
	* Add options page
	*/
	public function add_my_options_page() {
		$ops = add_options_page(
			'Easy PayPal Custom Fields',
			'Easy PayPal Custom Fields',
			'manage_options',
			'easy-paypal-custom-fields',
			array( &$this, 'echo_options_page' ) // callback function to draw the options page
		);
		// Add plugin CSS to this page
		add_action( 'admin_print_styles-' . $ops, array( &$this, 'add_css' ) );
		add_action( 'admin_print_styles-' . $ops, array( &$this, 'add_admin_js' ) );
	}
	
	
 /**
	* Add link options page from plugin init
	*/
	public function add_link_to_settings_page( $links, $file ) {
	
    static $this_plugin;
 
    if( !$this_plugin ) {
      $this_plugin = plugin_basename( __FILE__ );
    }
 
    if( $file == $this_plugin ) {
      $settings_link = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/options-general.php?page=easy-paypal-custom-fields">Settings</a>';
      array_unshift( $links, $settings_link );
    }
 
    return $links;
    
	}
	
	
	
/**
	* Draw the options page
	*/
	
	public function echo_options_page() {
		
		// Shorthand as there are some many checks to be made against button_type & theme
		$button_text = $this->opts['button_text'];
		$button_type = $this->opts['button_type'];
		$theme = $this->opts['theme'];		
		?>
	
		<div id="rps-inside" class="wrap">
		
			<div id="message" class="updated">
				<p>Thanks for downloading! If you like the plugin, feel free to give me a decent rating on the <a href="http://wordpress.org/extend/plugins/easy-paypal-custom-fields/">WordPress plugins site</a>.</p>
				<p>I do tweet from time to time too, if you'd like to follow me, my username is <a href="http://twitter.com/richardsweeney/">@richardsweeney</a></p>
			</div>
		
			<div id="icon-plugins" class="icon32"></div>
			<h2>Easy PayPal Custom Fields settings</h2>
			<form action="options.php" method="post">
			<?php
				// GO WP GO! Do those settings fields!
				settings_fields( 'rps_eppcf_options' );
				do_settings_sections( 'rps_eppcf_settings_page' );
			?>
				<br>
				<input name="Submit" type="submit" class="button-primary" id="submit" value="Save Changes" />
				
				<input type="hidden" id="plugin-url" value="<?php echo plugin_dir_url( __FILE__ ); ?>">
			</form>
			
			<h4 class="paypal-form"><br>Your Button will look like this:<br><br>
			
			<?php

				if( $theme == 'pp_large' ) {
					if( $button_type == 'Buy Now' ) {
						$args = array();
					} else if ( $button_type == 'Donations' ) {
						$args = array( 'src' => 'btn_donate_LG.gif' );
					}
				} else if( $theme == 'pp_small' ){
					if( $button_type == 'Buy Now' ) {
						$args = array( 'src' => 'btn_buynow_SM.gif' );
					} else if( $button_type == 'Donations' ) {
						$args = array( 'src' => 'btn_donate_SM.gif' );
					}
				} else if( $theme == 'light' ) {
					$args = array( 'type' => 'custom_theme', 'button_text' => $button_text );
				} else if( $theme == 'dark' ) {
					$args = array( 'type' => 'custom_theme', 'class' => 'rps-paypal-button-dark', 'button_text' => $button_text );
				} else if( $theme == 'blue' ) {
					$args = array( 'type' => 'custom_theme', 'class' => 'rps-paypal-button-blue', 'button_text' => $button_text );
				} else if( $theme == 'red' ) {
					$args = array( 'type' => 'custom_theme', 'class' => 'rps-paypal-button-red', 'button_text' => $button_text );
				}
				echo $this->type_of_button( $args );
			
			?>
					
			</h4>
			
		</div>
	
	<?php }
	
	
	
	public function create_settings_sections(){
	
		register_setting(
			'rps_eppcf_options',
			'rps_eppcf_options',
			array( &$this, 'validate_options' )
		);
	
		add_settings_section(
			'rps_eppcf_main',
			'Enter your default settings here',
			array( &$this, 'echo_settings_section_header' ),
			'rps_eppcf_settings_page'
		);
		
		$settings_fields = array(
			array(
				'id' => 'settings-input', //HTML ID tag for the section
				'text' => 'PayPal username',  // Text to output for the section
				'function' => array( &$this, 'echo_settings_input' ) // Callback function (to echo the form field)
			),
			array(
				'id' => 'settings-curreny',
				'text' => 'Default currency',
				'function' => array( &$this, 'echo_settings_currency' )
			),
			array(
				'id' => 'settings-button-type',
				'text' => 'Select a button type',
				'function' => array( &$this, 'echo_settings_button_type' )
			),
			array(
				'id' => 'settings-button-text',
				'text' => 'Custom button text (optional)',
				'function' => array( &$this, 'echo_settings_button_text' )
			),
			array(
				'id' => 'settings-url',
				'text' => 'Return URL (optional)',
				'function' => array( &$this, 'echo_settings_url' )
			),
			array(
				'id' => 'settings-post-type',
				'text' => 'Select on which post type to display the Button',
				'function' => array( &$this, 'echo_settings_post_type' )
			),
			array(
				'id' => 'settings-theme',
				'text' => 'Select a theme for the button',
				'function' => array( &$this, 'echo_settings_theme' )
			)
		);
		
		// Settings page on which to show the section - stays the same for all fields!
		$settings_page = 'rps_eppcf_settings_page';
		
		// Section of the settings page in which to show the form field as defined by the add_settings_section() function
		// stays the same for all fields
		$settings_sections = 'rps_eppcf_main';
	
		foreach( $settings_fields as $settings_field ) {
			add_settings_field(
				$settings_field[ 'id' ],
				$settings_field[ 'text' ],
				$settings_field[ 'function' ],
				$settings_page,
				$settings_sections
			);
		}
	
	}
	
	
 /**
	*	Draw settings page functions follow:
	*/
	public function echo_settings_section_header() {
		// echo '<p><strong>Plugin Settings:</strong></p>';
	}
	

	public function echo_settings_input() { ?>
		<input placeholder="email@address.com" id="user-email" name="rps_eppcf_options[user_email]" type="email" value="<?php echo $this->opts['user_email']; ?>" />
	<?php }
	
	
	public function echo_settings_currency() { ?>
		<select id="currency" name="rps_eppcf_options[currency]">
			<?php foreach( $this->currency_array as $key => $value ) : ?>
				<option value="<?php echo $value; ?>" <?php selected( $this->opts['currency'], $value ); ?>><?php echo $value; ?> (<?php echo $key; ?>)</option>
			<?php endforeach; ?>
		</select>
	<?php }
	
	
	public function echo_settings_button_type() { ?>
		<p>
			<select id="button-type" name="rps_eppcf_options[button_type]">
				<?php foreach( $this->button_type_array as $button ) : ?>
				<option value="<?php echo $button; ?>" <?php selected( $this->opts['button_type'], $button ); ?>>
					<?php echo $button; ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>
	<?php }
	
	
	public function echo_settings_button_text() { ?>
		<input placeholder="eg. 'Buy CD'" id="button-text" name="rps_eppcf_options[button_text]" type="text" value="<?php echo $this->opts['button_text']; ?>" />
	<?php }
	
	
	public function echo_settings_url() { ?>
		<input placeholder="The URL to return to after checkout" type="url" id="url" name="rps_eppcf_options[url]" value="<?php echo $this->opts['url']; ?>" />
	<?php }
	
	
	public function echo_settings_post_type() {
		
		// Get any custom post types
		$args = array(
	  	'public'   => true,
		  '_builtin' => false
		); 
		$output = 'names';
		$operator = 'and';
		$custom_post_types = get_post_types( $args, $output, $operator );
		
		// Regular 'post' and 'page' post types
		$regular_post_types = array(
			'post' => 'post',
			'page' => 'page'
		);
		
		if( isset( $custom_post_types ) ) {
			// Merge the array to get ALL post types
			$all_post_types = array_merge( $regular_post_types, $custom_post_types );
		} else {
			$all_post_types = $regular_post_types;
		}
		?>
		<p>
		<?php foreach( $all_post_types as $post_type ) : ?>
			<label>
				<input type="checkbox" name="rps_eppcf_options[post_type][]" value="<?php echo $post_type; ?>"
				<?php
					// to set the value using the checked() function for an array:
					// first check if the option value is selected (otherwise you'll get an invalid argument for the foreach loop)
					// if it is set, loop through the array to find the stored values and have the function mark them as checked
					if( isset( $this->opts['post_type'] ) ) {
						foreach( $this->opts['post_type'] as $checked ) {
		 					checked( $checked, $post_type );
	 					}
	 				}
	 			?>
	 			/>
			<?php echo $post_type; ?>
			</label>
			&nbsp;&nbsp;
		<?php endforeach; ?>
		</p>
	<?php
	}
	
	
	public function echo_settings_theme() { ?>
		<p>
			<select id="theme" name="rps_eppcf_options[theme]">
				<?php foreach( $this->button_theme_array as $key => $value ) : ?>
				<option value="<?php echo $key; ?>" <?php selected( $this->opts['theme'], $key ); ?>>
					<?php echo $value; ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php }
	
	/* End of draw settings sections functions */
	
	
 /**
	* Validate user input on options page
	*
	* @param array values from settings page
	* @return array of sanitized values
	*/
	public function validate_options( $input ) {
	
		$valid = array();
		
 		// PayPal username (email address)
		if( empty( $input['user_email'] ) ) {
				add_settings_error(
				'rps_eppcf_username',
				'rps_eppcf_texterror',
				'Please enter your PayPal username (email address)',
				'error'
			);
		} elseif( !is_email( $input['user_email'] ) ) {
			add_settings_error(
				'rps_eppcf_username',
				'rps_eppcf_texterror',
				'Please enter a valid email address',
				'error'
			);
		} else {
			$valid['user_email'] = sanitize_email( $input['user_email'] );
		}
		
		// Currency
		if( in_array( $input['currency'], $this->currency_array) ) {
			$valid['currency'] = $input['currency'];
		}
		
		// Button type
		if( in_array( $input['button_type'], $this->button_type_array ) ) {
			$valid['button_type'] = $input['button_type'];
		}
		
		// Button text
		if( isset( $input['button_text'] ) ) {
			$valid['button_text'] = trim( sanitize_text_field( $input['button_text'] ) );
		}
		
		// Return url
		if( isset( $input['url'] ) ) {
			$valid['url'] = esc_url_raw( $input['url'] );
		}
		
		// post type
		if( isset( $input['post_type'] ) ) {
			foreach( $input['post_type'] as $post_type ) {
				$post_type = sanitize_text_field( $post_type );
				$valid['post_type'][] = $post_type;	
			}
		}
		
		// Button theme
		if( array_key_exists( $input['theme'], $this->button_theme_array ) ) {
			$valid['theme'] = $input['theme'];
		}
	
		return $valid;
		
	}


 /**
	* Draw the meta box on page / post / custom post type
	* See http://codex.wordpress.org/Function_Reference/add_meta_box
	*/
	public function register_meta_box() {
		
		// Loop through the selected post types & create the meta box on the selected pages
		if( isset( $this->opts['post_type'] ) ) {
			foreach( $this->opts['post_type'] as $post_type ) {	
				add_meta_box(
				'rps_paypal_meta',
				'Add Paypal Button',
				array( &$this, 'echo_meta_box' ),
				$post_type,
				'normal',
				'high'
				);
			}
		}
		
	}
	


 /**
	*	Echo the meta box
	*/
	public function echo_meta_box( $post ) {
		
		// Get the current post meta & default settings
		$rps_meta_array = get_post_meta( $post->ID, '_rps_eppcf_array', true );
		$this->get_the_post_meta( $rps_meta_array );
		?>
		
		<div id="rps-inside">
		
			<p>
				<span>Show button</span>
				<select name="show" id="show">
					<option value="no" <?php selected( $this->my_post_meta['show'] , 'no' ); ?>>Don't show button / use shortcode</option>
					<option value="bottom" <?php selected( $this->my_post_meta['show'] , 'bottom' ); ?>>At bottom of post</option>
					<option value="top" <?php selected( $this->my_post_meta['show'] , 'top' ); ?>>At top of post</option>
				</select>
			</p>
		
			<p>
				<span>Product name *</span>
				<input placeholder="The name of the item for sale" type="text" name="name" id="name" value="<?php if( isset( $this->my_post_meta['name']) ) echo $this->my_post_meta['name']; ?>" />
			</p>
			
			<p>
				<span>Item number (optional)</span>
				<input type="text" name="item_no" id="item_no" value="<?php echo $this->my_post_meta['item_no']; ?>">
			</p>	
					
			<p>
				<span>Amount **</span>
				<input placeholder="eg 9.99" type="text" id="amount" name="amount" value="<?php echo $this->my_post_meta['amount']; ?>" />
			</p>
			
			<p>
				<span>Postage (optional)</span>
				<input placeholder="eg 1.99" type="text" id="postage" name="postage" value="<?php echo $this->my_post_meta['postage']; ?>" />
			</p>
			
			<p>
				<span>Require customers' address<br />(Buy Now only)</span>
				<label>
					<input type="checkbox" id="require_address" name="require_address" value="true" <?php checked( $this->my_post_meta['require_address'], true ); ?> />
					Yes
				</label>
			</p>
			
			<p>
				<span>Allow customer to select quantity</span>
				<label>
					<input type="checkbox" id="quantity_checkbox" name="quantity_checkbox" value="true" <?php checked( $this->my_post_meta['quantity_checkbox'], true ); ?> />
					Yes
				</label>
			</p>
			
			<p>
				<span>Add a custom textfield</span>
				<label>
					<input type="checkbox" id="textfield_checkbox" name="textfield_checkbox" value="true" <?php checked( $this->my_post_meta['textfield_checkbox'], true ); ?> />
					Yes
				</label>
			</p>
			
			<p id="custom_textfield">
				<span>Title for the textfield</span>
				<input placeholder="add a title for the textfield" type="text" id="textfield_title" name="textfield_title" value="<?php echo $this->my_post_meta['textfield_title']; ?>" />
			</p>
			
		<!--
			<p>
				<span>Add drop-down menu (optional):</span>
				<input placeholder="title for the menu" type="text" id="drop_down_title" name="drop_down_title" value="<?php echo $this->my_post_meta['drop_down_title']; ?>" />
			</p>
		-->
		
		<p>* Leave this field blank to allow the customer to enter their own name for the item</p>
		<p>** <strong>Don't use a currency symbol!</strong> Leave this field blank to allow the customer to enter their own amount on the PayPal payment page</p>
		
		<div id="rps-settings-box">
		
			<p>
				<br>
				<span>Paypal username</span>
				<input placeholder="email@address.com" type="email" id="user_email" name="user_email" value="<?php echo antispambot( $this->my_post_meta['user_email'] ); ?>" />
			</p>
			
			<p>
				<span>Custom button text</span>
				<input placeholder="eg. 'Buy CD'" type="text" id="button_text" name="button_text" value="<?php echo $this->my_post_meta['button_text']; ?>" />
			</p>
			
			<p>
				<span>Currency</span>
				<select id="currency" name="currency">
					<?php foreach($this->currency_array as $key => $value) : ?>
						<option value="<?php echo $value; ?>" <?php selected( $this->my_post_meta['currency'], $value ); ?>><?php echo $value; ?> (<?php echo $key; ?>)</option>
					<?php endforeach; ?>
				</select>
			</p>
			
			<p>
				<span>Button Type</span>
				<select id="button_type" name="button_type">
					<?php foreach( $this->button_type_array as $button ) : ?>
					<option value="<?php echo $button; ?>" <?php selected( $this->my_post_meta['button_type'], $button ); ?>>
						<?php echo $button; ?>
					</option>
					<?php endforeach; ?>
				</select>
			</p>
			
			<p>
				<span>Return Url (optional)</span>
				<input placeholder="The full URL to return to after checkout" type="url" id="url" name="url" value="<?php echo $this->my_post_meta['url']; ?>" />
			</p>
			
			<p>
				<span>Theme</span>
				<select id="theme" name="theme">
					<?php foreach( $this->button_theme_array as $key => $value ) : ?>
					<option value="<?php echo $key; ?>" <?php selected( $this->my_post_meta['theme'], $key ); ?>>
						<?php echo $value; ?>
					</option>
					<?php endforeach; ?>
				</select>
			</p>
		
		</div>
		
		<p><br />If you want to add the button anywhere in the post you can copy &amp; paste the shortcode
		<span id="rps-shortcode">[rps-paypal]</span>
		wherever you'd like the button to appear. <a href="http://en.support.wordpress.com/shortcodes/">What's a shortcode?</a></p>
		
		</div>
	
	<?php }
	
	
	
 /**
	* Save post meta
	*/
	public function save_eppcf_meta( $post_id ) {
			
		// Check user permissions
		if( !current_user_can( 'edit_posts' ) ) {
			wp_die( "You don't have permission to do that!" );
		}
		
		// Check if WP is autosaving the post. Stops WP from deleting meta values - thanks WP :P.
	 	if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
	    return $post_id;
	  }
	  
		// Create the array to store the sanitized values
		$eppcf_array = array();
	  
		// Helper array to perform basic sanitization on multiple vals
	  $textfield_array = array(
	  	'item_no', // Item number
	  	'button_text', // Button text
	  	'textfield_title' // Title of the textarea
	  	// 'drop_down_title' // Title of drop down menu
	  );
	  foreach( $textfield_array as $textfield ){
		  if( isset( $_POST[$textfield] ) ){
		  	$eppcf_array[$textfield] = trim( sanitize_text_field( $_POST[$textfield] ) );
	  	}
	  }
		
		// Check against this array of values
		$show_array = array( 'no', 'bottom', 'top' );
		// Where to show the button
		if( isset( $_POST['show'] ) && in_array( $_POST['show'], $show_array ) ) {
			$eppcf_array['show'] = $_POST['show'];
		} else {
			$eppcf_array['show'] = 'no';
		}
		
		// Name of thing for sale
		if( isset( $_POST['name'] ) ) {
			$eppcf_array['name'] = trim( sanitize_text_field( $_POST['name'] ) );
		} else {
			$eppcf_array['name'] = '';
		}
		
		$checkboxVals = array( 'require_address', 'textfield_checkbox', 'quantity_checkbox' );
		
		foreach( $checkboxVals as $checkboxVal ){
			if( isset( $_POST[ $checkboxVal ] ) && $_POST[ $checkboxVal ] == 'true' ){
				$eppcf_array[ $checkboxVal ] = true;
			} else {
				$eppcf_array[ $checkboxVal ] = false;
			}
		}
		
		// PayPal username (email address)
		if( isset( $_POST['user_email'] ) && is_email( $_POST['user_email'] ) ) {
			$eppcf_array['user_email'] = trim( sanitize_email( $_POST['user_email'] ) );
		}
		
		// Currency
		if( isset( $_POST['currency'] ) && in_array( $_POST['currency'], $this->currency_array ) ){
			$eppcf_array['currency'] = $_POST['currency'];
		}
		
		// Amount
		if( isset( $_POST['amount'] ) && is_numeric( $_POST['amount'] ) ) {
			$eppcf_array['amount'] = trim( $_POST['amount'] );
		}
		
		// Postage
		if( isset( $_POST['postage'] ) && is_numeric( $_POST['postage'] ) ) {
			$eppcf_array['postage'] = trim( $_POST['postage'] );
		}
		
		// Return url
		if( isset( $_POST['url'] ) ) {
			$eppcf_array['url'] = trim( esc_url_raw( $_POST['url'] ) );
		}
		
		// Button type
		if( isset( $_POST['button_type'] ) && in_array( $_POST['button_type'], $this->button_type_array ) ) {
			$eppcf_array['button_type'] = $_POST['button_type'];
		}
		
		// Button theme
		if( isset( $_POST['theme'] ) && array_key_exists( $_POST['theme'], $this->button_theme_array ) ) {
			$eppcf_array['theme'] = $_POST['theme'];
		}
		
		// Update or add post meta
		update_post_meta( $post_id, '_rps_eppcf_array', $eppcf_array );
		
	}
	
	
 /**
 	* Helper to draw the revelant type of button
 	*
 	* @param array type of button, src of button, button id + button text
 	* @return HTML blob of proper button input type (img or button)
 	*/	
	private function type_of_button( $args ) {
		$defaults = array(
			'type' => 'paypal_image',
			'src' => 'btn_buynow_LG.gif',
			'class' => 'rps-paypal-button-light',
			'button_text' => 'Checkout with PayPal'
		);
		$args = wp_parse_args( $args, $defaults );
		if( $args['type'] == 'paypal_image' ){
			return '<input type="image" id="eppcf-button" class="eppcf-image-button" src="' . plugin_dir_url( __FILE__ ) . 'images/' . $args['src'] . '" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">';
		} else if( $args['type'] == 'custom_theme' ){
			return '<input type="submit" id="eppcf-button" class="rps-custom-theme-button ' . $args['class'] . '" value="' . $args['button_text'] . '">';
		}
	}
	
	
 /**
	*  Echo PayPal button
	*/
	public function create_button(){
		
		global $post;
		// Get post meta
		$rps_meta_array = get_post_meta( $post->ID, '_rps_eppcf_array', true );
		$this->get_the_post_meta( $rps_meta_array );
		
		// Shorthand
		$theme = $this->my_post_meta['theme'];
		$button_type = $this->my_post_meta['button_type'];
		$button_text = $this->my_post_meta['button_text'];
		$tf_i = 0;
		
		/*
			I chose not to add the drop-down menu option.
			Only one person asked for it and I think it makes things overly complicated.
			KISS
		*/
		
		// If there are 2 additional textfields, they must have different IDs
/*
		if( empty( $this->my_post_meta['text_field'] ) && !empty( $this->my_post_meta['drop_down_title'] ) ) {
			$dd_i = 0;
		} else if( empty( $this->my_post_meta['text_field'] ) && !empty( $this->my_post_meta['drop_down_title'] ) ) {
			$tf_i = 0;
		} else if( !empty( $this->my_post_meta['text_field'] ) && !empty( $this->my_post_meta['drop_down_title'] ) ) {
			$tf_i = 0;
			$dd_i = 1;
		}
*/
		
		// Button HTML
		$button = '<div class="paypal-form">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
		
		// Text field
		if( $this->my_post_meta['textfield_checkbox'] == true ) {
			$button .= '<table>
				<tr><td><input type="hidden" name="on' . $tf_i . '" value="' . $this->my_post_meta['textfield_title'] . '">' . $this->my_post_meta['textfield_title'] . '</td></tr>
				<tr><td><input type="text" name="os' . $tf_i . '" maxlength="200"></td></tr>
			</table>';
		}
		
		// Drop down menu
/*
		if( !empty( $this->my_post_meta['drop_down_title'] ) ) {
			$button .= '<table>
				<tr><td><input type="hidden" name="on' . $dd_i . '" value="' . $this->my_post_meta['drop_down_title'] . '">' . $this->my_post_meta['drop_down_title'] . '</td></tr>
				<tr><td>
					<select name="os' . $dd_i . '">
						<option value="one">one </option>
						<option value="two">two </option>
						<option value="three">three </option>
					</select>
				</td></tr>
			</table>';
		}
*/
		
		// Button type and theme
		if( $button_type == 'Buy Now' ) {
			$button .= '<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="button_subtype" value="services">
			<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">';	
		} else if( $button_type == 'Donations' ) {
			$button .= '<input type="hidden" name="cmd" value="_donations">
			<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">';
		}
		
		switch( $theme ){
			case 'pp_large' :
				if( $button_type == 'Buy Now' ) {
					$args = array( 'src' => 'btn_buynow_LG.gif' );
				} else if ( $button_type == 'Donations' ) {
					$args = array( 'src' => 'btn_donate_LG.gif' );
				}
				break;
			case 'pp_small' :
				if( $button_type == 'Buy Now' ) {
					$args = array( 'src' => 'btn_buynow_SM.gif' );
				} else if( $button_type == 'Donations' ) {
					$args = array( 'src' => 'btn_donate_SM.gif' );
				}
				break;
			case 'light' :
				$args = array( 'type' => 'custom_theme', 'button_text' => $button_text );
				break;
			case 'dark' :
				$args = array( 'type' => 'custom_theme', 'class' => 'rps-paypal-button-dark', 'button_text' => $button_text );
				break;
			case 'blue' :
				$args = array( 'type' => 'custom_theme', 'class' => 'rps-paypal-button-blue', 'button_text' => $button_text );
				break;
			case 'red' :
				$args = array( 'type' => 'custom_theme', 'class' => 'rps-paypal-button-red', 'button_text' => $button_text );
				break;
		}
		
		/* Helper function */
		$button .= $this->type_of_button( $args );
		
		// Username (email address)
		$button .= '<input type="hidden" name="business" value="' . antispambot( $this->my_post_meta['user_email'] ) .'" />';
		
		// Name of item for sale
		if( !empty( $this->my_post_meta['name'] ) ) {
			$button .= '<input type="hidden" name="item_name" value="' . $this->my_post_meta['name'] . '" />';
		}
		
		// Item number
		if( !empty( $this->my_post_meta['item_no'] ) ) {
			$button .= '<input type="hidden" name="item_number" value="' . $this->my_post_meta['item_no'] . '" />';
		}
		
		// ammount, currency, postage
		$button .= '
			<input type="hidden" name="amount" value="' . $this->my_post_meta['amount'] . '" />
			<input type="hidden" name="currency_code" value="' . $this->my_post_meta['currency'] . '" />';
		
		if( $button_type == 'Buy Now' ) {
			$button .= '<input type="hidden" name="shipping" value="' . $this->my_post_meta['postage'] . '" />';
		}
		
		$requireAddress = ( $this->my_post_meta['require_address'] == true ) ? 2 : 1;
		
		$button .= '<input type="hidden" name="no_shipping" value="' . $requireAddress . '">
			<input type="hidden" name="rm" value="2" />';
		
		if( $button_type == 'Buy Now' && $this->my_post_meta['quantity_checkbox'] == true ) {
			$button .= '<input type="hidden" name="undefined_quantity" value="1">';
		}
		
		// Return url
		if( !empty( $this->my_post_meta['url'] ) ) {
			$button .= '<input type="hidden" name="return" value="' . $this->my_post_meta['url'] . '" />';
		}

		$button .= '</form>
		</div>';
		
		// Store the button as $paypal_button, visible throughout the class
		$this->paypal_button = $button;

	}
	
	
	
 /**
	* Where the button should appear
	*
	* @param string the post content
	* @return string modified post content
	*/
	public function display_button( $content ) {
	
		global $post;
		// Get the current post meta
		$rps_meta_array = get_post_meta( $post->ID, '_rps_eppcf_array', true );
		$show = ( isset( $rps_meta_array['show'] ) ) ? $rps_meta_array['show'] : 'no';
		// Create the button
		$this->create_button();
		
		// Determine where to show the button based on value of $show
		if( $show != 'no' ) {
			switch( $show ) {
				case 'bottom':
					return $content . $this->paypal_button;
				break;
				case 'top':
					return $this->paypal_button . $content;
				break;
				default:
					return $content;
			}
		} else {
			return $content;
		}
		
	}
	


 /**
	* Create button shortcode
	*/
	function create_shortcode() {
		$this->create_button();
		return $this->paypal_button;
	}
	
}


/**
	* Create an object, initialize the class, have a beer, kiss your lady/man/ladyboy.
	*
	* OOP rocks, so why not use it for WordPress? Lots of benefits, mostly not
	* having to prefix all my functions makes it all super worth it.
	*/
	$rps_eppcf = new RPS_eppcf();
	
?>