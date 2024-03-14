<?php
/**
 * Marketing Automation by Gist
 * 
 * The official Gist Wordpress plugin.
 * 
 * @package ConvertFox
 * @global object $WP_ConvertFox
 * @author Jitta Raghavender Rao <jitta@getgist.com>
 */
/*
Plugin Name: Gist All-In-One Marketing - Live Chat, Popups, Email
Plugin URI: https://getgist.com?utm_source=wordpress-plugin&utm_campaign=wordpress&utm_medium=marketplaces
Description: One platform giving you all the tools you need to market your business, sell your products and services and support your customers. All-in-One Growth Software.
Version: 2.7
Author: Gist
Author URI: https://getgist.com
License: GPLv2
*/

namespace convertfox;

class WP_CONVERTFOX {
	/**
	 * Instantiate a new instance
	 */
	 public function __construct() {
        if(is_admin()) {
	    	add_action('admin_menu', array($this, 'add_settings_page'));
	    	add_action('admin_init', array($this, 'convertfox_init'));
            add_action('admin_enqueue_scripts', array($this, 'convertfox_enqueue_scripts'));
		} else {
			require_once dirname( __FILE__ ) . '/page.php';
		}
    }

    public function convertfox_enqueue_scripts() {
        wp_enqueue_script( 'convertfox-script', plugin_dir_url(__FILE__) . 'script.js', array(), '1.0' );
    }

	public function add_settings_page() {
        // This page will be under "Settings"
		add_options_page('Gist', 'Gist', 'manage_options', 'convertfox-admin', array($this, 'create_settings_page'));
    }

	public function create_settings_page() {
	?>
	<div class="wrap">
	    <h2>Gist Settings</h2>

	    <form method="post" action="options.php">
	    <?php
            // This prints out all hidden setting fields
		    settings_fields('convertfox_settings_group');
		    do_settings_sections('convertfox_options');
		?>
	        <?php submit_button(); ?>
	    </form>
	    <p>Having trouble and need some help? Here's <a href='https://docs.getgist.com/article/168-install-gist-on-your-wordpress-site' target='_blank'>a step-by-step guide</a> to get you started.</p>
	</div>
	<?php
    }

	public function print_main_section_info() {
		print "Enter your Gist settings below to control how WordPress integrates with your Gist workspace.";
    }

    public function print_messenger_visibility_section_info() {
    	echo "<hr />";
    	echo "<h3>Messenger visibility</h3>";
		echo "Choose which pages Messenger should appear on (By default, appears on all pages):";
    }

	function my_text_input( $args ) {
	    $name = esc_attr( $args['name'] );
	    $value = esc_attr( $args['value'] );
	    if(strlen($value) > 0) {
	    	$size = strlen($value) + 2;
	    } else {
	    	$size = 25;
	    }
	    echo "<input type='text' name='$name' size='$size' value='$value' />";
	    echo "<p class='description'>If you already have an account, <a href='https://app.getgist.com/projects/_/settings/tracking-code' target='_blank'>click here to retrieve your Workspace ID</a>.<br>If you don't have a Gist account, you can <a href='https://getgist.com/pricing/' target='_blank'>sign up for one here</a>.</p>";

	}

	/** 
	 * Output the input for the enabled option
	 */
	public function admin_option_is_enabled() {
		$settings = (array) get_option( 'convertfox_settings' );
		$temp_checked = "";
		if ( isset($settings['is_enabled']) && $settings['is_enabled'] )
			$temp_checked = 'checked="checked"';
		echo "<label><input type='checkbox' name='convertfox_settings[is_enabled]' value='1' " . 
			 $temp_checked . " /> " .
			"Add tracking code to all pages</label>";
	}

	public function admin_option_identify_users() {
		$settings = (array) get_option( 'convertfox_settings' );
		$temp_checked = "";
		if ( isset($settings['identify_users']) && $settings['identify_users'] )
			$temp_checked = 'checked="checked"';
		echo "<label><input type='checkbox' name='convertfox_settings[identify_users]' value='1' " . 
			 $temp_checked . " /> " .
			"Sync logged-in WordPress users as Contacts to your Gist workspace</label>";
	}

    public function admin_option_identity_verify_users() {
        $settings = (array) get_option( 'convertfox_settings' );
        $temp_checked = "";
        if ( isset($settings['identity_verify_users']) && $settings['identity_verify_users'] )
            $temp_checked = 'checked="checked"';
        echo "<label><input type='checkbox' name='convertfox_settings[identity_verify_users]' value='1' " .
            $temp_checked . " /> " .
            "Enabled Identity Verification for logged-in users.</label>";
    }

    public function admin_option_identity_secret_key() {
        $settings = (array) get_option( 'convertfox_settings' );
        $value = '';
        if ( isset($settings['identity_secret_key']) && $settings['identity_secret_key'] )
            $value = $settings['identity_secret_key'];
        echo "<label><input type='text' name='convertfox_settings[identity_secret_key]' value='". $value ."' /></label>";
    }

	public function admin_option_disable_for_admin() {
		$settings = (array) get_option( 'convertfox_settings' );
		$temp_checked = "";
		if ( isset($settings['disable_for_admin']) && $settings['disable_for_admin'] )
			$temp_checked = 'checked="checked"';
		echo "<label><input type='checkbox' name='convertfox_settings[disable_for_admin]' value='1' " . 
			 $temp_checked . " /> " .
			"Disable tracking for WordPress admin users</label>";
	}

	public function admin_option_messenger_visibility_front_page() {
		$settings = (array) get_option( 'convertfox_settings' );
		$temp_checked = "";
		if ( isset($settings['messenger_visibility_front_page']) && $settings['messenger_visibility_front_page'] )
			$temp_checked = 'checked="checked"';
		echo "<label><input type='checkbox' name='convertfox_settings[messenger_visibility_front_page]' value='1' " . 
			 $temp_checked . " /> " .
			"</label>";
	}

	public function admin_option_messenger_visibility_pages() {
		$settings = (array) get_option( 'convertfox_settings' );
		$temp_checked = "";
		if ( isset($settings['messenger_visibility_pages']) && $settings['messenger_visibility_pages'] )
			$temp_checked = 'checked="checked"';
		echo "<label><input type='checkbox' name='convertfox_settings[messenger_visibility_pages]' value='1' " . 
			 $temp_checked . " /> " .
			"</label>";
	}

	public function admin_option_messenger_visibility_blog_home() {
		$settings = (array) get_option( 'convertfox_settings' );
		$temp_checked = "";
		if ( isset($settings['messenger_visibility_blog_home']) && $settings['messenger_visibility_blog_home'] )
			$temp_checked = 'checked="checked"';
		echo "<label><input type='checkbox' name='convertfox_settings[messenger_visibility_blog_home]' value='1' " . 
			 $temp_checked . " /> " .
			"</label>";
	}

	public function admin_option_messenger_visibility_posts() {
		$settings = (array) get_option( 'convertfox_settings' );
		$temp_checked = "";
		if ( isset($settings['messenger_visibility_posts']) && $settings['messenger_visibility_posts'] )
			$temp_checked = 'checked="checked"';
		echo "<label><input type='checkbox' name='convertfox_settings[messenger_visibility_posts]' value='1' " . 
			 $temp_checked . " /> " .
			"</label>";
	}

	public function admin_option_messenger_visibility_archives() {
		$settings = (array) get_option( 'convertfox_settings' );
		$temp_checked = "";
		if ( isset($settings['messenger_visibility_archives']) && $settings['messenger_visibility_archives'] )
			$temp_checked = 'checked="checked"';
		echo "<label><input type='checkbox' name='convertfox_settings[messenger_visibility_archives]' value='1' " . 
			 $temp_checked . " /> " .
			"</label>";
	}

    public function convertfox_init() {
		register_setting('convertfox_settings_group', 'convertfox_settings', array($this, 'validate'));
      	$settings = (array) get_option( 'convertfox_settings' );

        add_settings_section(
		    'convertfox_settings_section',
		    '',
		    array($this, 'print_main_section_info'),
		    'convertfox_options'
		);

		add_settings_field(
		    'project_id',
		    'Gist Workspace ID', // human readable part
		    array($this, 'my_text_input'),  // the function that renders the field
	    	'convertfox_options',
	    	'convertfox_settings_section',
	    	array(
		    	'name' => 'convertfox_settings[project_id]',
		    	'value' => isset($settings['project_id']) ? $settings['project_id'] : "",
			)
		);

		add_settings_field(
		    'convertfox_is_enabled',
		    'Enable', // human readable part
		    array($this, 'admin_option_is_enabled'),  // the function that renders the field
	    	'convertfox_options',
	    	'convertfox_settings_section',
	    	array(
		    	'name' => 'convertfox_settings[is_enabled]',
		    	'value' => isset($settings['is_enabled']) ? $settings['is_enabled'] : "",
			)
		);

		add_settings_field(
		    'convertfox_disable_for_admin',
		    'Disable for admin users', // human readable part
		    array($this, 'admin_option_disable_for_admin'),  // the function that renders the field
	    	'convertfox_options',
	    	'convertfox_settings_section',
	    	array(
		    	'name' => 'convertfox_settings[disable_for_admin]',
		    	'value' => isset($settings['disable_for_admin']) ? $settings['disable_for_admin'] : "",
			)
		);

		add_settings_field(
		    'convertfox_identify_users',
		    'Sync WordPress users', // human readable part
		    array($this, 'admin_option_identify_users'),  // the function that renders the field
	    	'convertfox_options',
	    	'convertfox_settings_section',
	    	array(
		    	'name' => 'convertfox_settings[identify_users]',
		    	'value' => isset($settings['identify_users']) ? $settings['identify_users'] : "",
			)
		);

        add_settings_field(
            'convertfox_identity_verification_for_users',
            'Enable Identity Verification', // human readable part
            array($this, 'admin_option_identity_verify_users'),  // the function that renders the field
            'convertfox_options',
            'convertfox_settings_section',
            array(
                'name' => 'convertfox_settings[identity_verify_users]',
                'value' => isset($settings['identity_verify_users']) ? $settings['identity_verify_users'] : "",
            )
        );

        add_settings_field(
            'convertfox_identity_secret_key',
            'Verification secret key', // human readable part
            array($this, 'admin_option_identity_secret_key'),  // the function that renders the field
            'convertfox_options',
            'convertfox_settings_section',
            array(
                'name' => 'convertfox_settings[identity_secret_key]',
                'value' => isset($settings['identity_secret_key']) ? $settings['identity_secret_key'] : "",
            )
        );

		add_settings_section(
		    'convertfox_settings_messenger_visibility_section',
		    '',
		    array($this, 'print_messenger_visibility_section_info'),
		    'convertfox_options'
		);

		add_settings_field(
		    'convertfox_messenger_visibility_front_page',
		    'Hide on front page', // human readable part
		    array($this, 'admin_option_messenger_visibility_front_page'),  // the function that renders the field
	    	'convertfox_options',
	    	'convertfox_settings_messenger_visibility_section',
	    	array(
		    	'name' => 'convertfox_settings[messenger_visibility_front_page]',
		    	'value' => isset($settings['messenger_visibility_front_page']) ? $settings['messenger_visibility_front_page'] : "",
			)
		);

		add_settings_field(
		    'convertfox_messenger_visibility_blog_home',
		    'Hide on blog home', // human readable part
		    array($this, 'admin_option_messenger_visibility_blog_home'),  // the function that renders the field
	    	'convertfox_options',
	    	'convertfox_settings_messenger_visibility_section',
	    	array(
		    	'name' => 'convertfox_settings[messenger_visibility_blog_home]',
		    	'value' => isset($settings['messenger_visibility_blog_home']) ? $settings['messenger_visibility_blog_home'] : "",
			)
		);

		add_settings_field(
		    'convertfox_messenger_visibility_pages',
		    'Hide on all pages', // human readable part
		    array($this, 'admin_option_messenger_visibility_pages'),  // the function that renders the field
	    	'convertfox_options',
	    	'convertfox_settings_messenger_visibility_section',
	    	array(
		    	'name' => 'convertfox_settings[messenger_visibility_pages]',
		    	'value' => isset($settings['messenger_visibility_pages']) ? $settings['messenger_visibility_pages'] : "",
			)
		);

		add_settings_field(
		    'convertfox_messenger_visibility_posts',
		    'Hide on all posts', // human readable part
		    array($this, 'admin_option_messenger_visibility_posts'),  // the function that renders the field
	    	'convertfox_options',
	    	'convertfox_settings_messenger_visibility_section',
	    	array(
		    	'name' => 'convertfox_settings[messenger_visibility_posts]',
		    	'value' => isset($settings['messenger_visibility_posts']) ? $settings['messenger_visibility_posts'] : "",
			)
		);

		add_settings_field(
		    'convertfox_messenger_visibility_archives',
		    'Hide on all archives', // human readable part
		    array($this, 'admin_option_messenger_visibility_archives'),  // the function that renders the field
	    	'convertfox_options',
	    	'convertfox_settings_messenger_visibility_section',
	    	array(
		    	'name' => 'convertfox_settings[messenger_visibility_archives]',
		    	'value' => isset($settings['messenger_visibility_archives']) ? $settings['messenger_visibility_archives'] : "",
			)
		);
	}

	public function validate( $input ) {
		$output = get_option( 'convertfox_settings' );
	    if ( ctype_alnum( $input['project_id'] ) || $input['project_id'] == "" ) {
	        $output['project_id'] = $input['project_id'];
	    } else {
	    	echo "Adding Error \n"; #die;
	        add_settings_error( 'convertfox_options', 'project_id', 'The Gist Workspace ID you entered is invalid (should be alpha numeric)' );
	    }

	    if ( isset( $input['is_enabled'] ) ) {
	      $output['is_enabled'] = $input['is_enabled'] = true;
	    } else {
	      $output['is_enabled'] = false;
	    }

	    if ( isset( $input['disable_for_admin'] ) ) {
	      $output['disable_for_admin'] = $input['disable_for_admin'] = true;
	    } else {
	      $output['disable_for_admin'] = false;
	    }

	    if ( isset( $input['identify_users'] ) ) {
	      $output['identify_users'] = $input['identify_users'] = true;
	    } else {
	      $output['identify_users'] = false;
	    }

        if ( isset( $input['identity_verify_users'] ) && $input['identity_verify_users'] == true ) {
            if($input['identity_secret_key'] == "") {
                echo "Adding Error \n"; #die;
                add_settings_error( 'convertfox_options', 'identity_secret_key', 'Identity verification secret cannot be empty' );
            } else {
                $output['identity_verify_users'] = $input['identity_verify_users'] = true;
                $output['identity_secret_key'] = $input['identity_secret_key'];
            }
        } else {
            $output['identity_verify_users'] = false;
        }

	    if ( isset( $input['messenger_visibility_front_page'] ) ) {
	      $output['messenger_visibility_front_page'] = $input['messenger_visibility_front_page'] = true;
	    } else {
	      $output['messenger_visibility_front_page'] = false;
	    }

	    if ( isset( $input['messenger_visibility_pages'] ) ) {
	      $output['messenger_visibility_pages'] = $input['messenger_visibility_pages'] = true;
	    } else {
	      $output['messenger_visibility_pages'] = false;
	    }

	    if ( isset( $input['messenger_visibility_blog_home'] ) ) {
	      $output['messenger_visibility_blog_home'] = $input['messenger_visibility_blog_home'] = true;
	    } else {
	      $output['messenger_visibility_blog_home'] = false;
	    }

	    if ( isset( $input['messenger_visibility_posts'] ) ) {
	      $output['messenger_visibility_posts'] = $input['messenger_visibility_posts'] = true;
	    } else {
	      $output['messenger_visibility_posts'] = false;
	    }

	    if ( isset( $input['messenger_visibility_archives'] ) ) {
	      $output['messenger_visibility_archives'] = $input['messenger_visibility_archives'] = true;
	    } else {
	      $output['messenger_visibility_archives'] = false;
	    }

	    return $output;
	}
}
$ConvertFox = new \convertfox\WP_CONVERTFOX();
?>