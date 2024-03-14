<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Social_Share_With_Floating_Bar_Settings {

	/**
	 * The single instance of Social_Share_With_Floating_Bar_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'sswfb_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item () {
		$page = add_options_page( __( 'Social Share with Floating Bar Settings', 'social-share-with-floating-bar' ) , __( 'Social Share With Floating Bar', 'social-share-with-floating-bar' ) , 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'social-share-with-floating-bar' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {

		$settings['post_share'] = array(
			'title'					=> __( 'Post Share Buttons', 'social-share-with-floating-bar' ),
			'description'			=> __( 'Options for showing social sharing buttons on posts', 'social-share-with-floating-bar' ),
			'fields'				=> array(
				array(
					'id' 			=> 'share_type',
					'label'			=> __( 'Share Button Type', 'social-share-with-floating-bar' ),
					'description'	=> __( 'Show basic share buttons, or share buttons that also show the number of shares.', 'social-share-with-floating-bar' ),
					'type'			=> 'radio',
					'options'		=> array( 'basic' => 'Social Icon Only', 'text' => 'Social Icon and Text'),
					'default'		=> 'text'
				),
				array(
					'id' 			=> 'share_location',
					'label'			=> __( 'Share Button Location', 'social-share-with-floating-bar' ),
					'description'	=> __( 'Select where you would like the sharing buttons to display.', 'social-share-with-floating-bar' ),
					'type'			=> 'checkbox_multi',
					'options'		=> array( 'before' => 'Before Post', 'after' => 'After Post', 'floating-left' => 'Floating Bar Left', 'floating-right' => 'Floating Bar Right'),
					'default'		=> array( 'before' )
				),
				array(
					'id' 			=> 'social_sites',
					'label'			=> __( 'Social Sites', 'social-share-with-floating-bar' ),
					'description'	=> __( 'Select which sharing buttons you would like to display.', 'social-share-with-floating-bar' ),
					'type'			=> 'checkbox_multi',
					'options'		=> array( 'facebook' => 'Facebook', 'twitter' => 'Twitter', 'reddit' => 'Reddit', 'pinterest' => 'Pinterest', 'vk' => 'VK', 'email' => 'Email'),
					'default'		=> array( 'facebook', 'twitter','reddit' ,'pinterest', 'vk', 'email' )
				),
				array(
					'id' 			=> 'facebook_app_id',
					'label'			=> __( 'Facebook App Id' , 'social-share-with-floating-bar' ),
					'description'	=> __( 'Facebook Sharing requires that you register an app with Facebook. See Documentation for instructions.', 'social-share-with-floating-bar' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( '', 'social-share-with-floating-bar' )
				),
				array(
					'id'			=> 'facebook_app_token',
					'label'			=> __( 'Facebook App Token', 'social-share-with-floating-bar' ),
					'description'	=> __( 'Facebook App Token allows your site to make Facebook API requests. See Documentation for Instructions related to getting an App Token.', 'social-share-with-floating-bar' ),
					'type'			=> 'password',
					'default'		=> '',
					'placeholder'	=> ''
				)
			)
		);

		$settings['media_share'] = array(
			'title'					=> __( 'Media Share Buttons', 'social-share-with-floating-bar' ),
			'description'			=> __( 'Options for displaying share buttons on post media.', 'social-share-with-floating-bar' ),
			'fields'				=> array(
				array(
					'id' 			=> 'show_media_buttons',
					'label'			=> __( 'Display Media Share Buttons', 'social-share-with-floating-bar' ),
					'description'	=> __( '', 'social-share-with-floating-bar' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'media_social_sites',
					'label'			=> __( 'Social Sites', 'social-share-with-floating-bar' ),
					'description'	=> __( 'Select which sharing buttons you would like to display.', 'social-share-with-floating-bar' ),
					'type'			=> 'checkbox_multi',
					'options'		=> array( 'pinterest' => 'Pinterest', 'email' => 'Email', 'link' => 'Direct Link'),
					'default'		=> array( 'facebook', 'pinterest' )
				)
			)
		);

		$settings['advanced'] = array(
			'title'					=> __( 'Advanced Options', 'social-share-with-floating-bar' ),
			'description'			=> __( 'More customization for experienced Wordpress Theme developers.', 'social-share-with-floating-bar' ),
			'fields'				=> array(
				array(
					'id' 			=> 'load_css',
					'label'			=> __( 'Disable plugin CSS', 'social-share-with-floating-bar' ),
					'description'	=> __( 'Select this if you want to include this plugin\'s CSS within your theme\'s CSS to reduce HTTP requests and increase page load speed, or if you want to customize the buttons with your own styles.', 'social-share-with-floating-bar' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'disable_js',
					'label'			=> __( 'Disable plugin javascript', 'social-share-with-floating-bar' ),
					'description'	=> __( 'Select this if you want to include this plugin\'s scripts within your theme\'s scripts to reduce HTTP requests and increase page load speed.', 'social-share-with-floating-bar' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				)
			)
		);

		$settings['documentation'] = array(
			'title'					=> __( 'Documentation', 'social-share-with-floating-bar' ),
			'description'			=> __( 'More Description for experienced Wordpress Theme developers click here.', 'social-share-with-floating-bar' ),
			'fields'				=> array(
				array(
					'id' 			=> 'doc_1',
					'label'			=> __( 'Shortcodes', 'social-share-with-floating-bar' ),
					'description'	=> __( '[sswfb_post_share]</br>
						[sswfb_post_share share_type="basic"] </br>' ),
					'type'			=> 'tex',
					'default'		=> ''
				),
				array(
					'id' 			=> 'doc_2',
					'label'			=> __( 'Facebook Settings App ID', 'social-share-with-floating-bar' ),
					'description'	=> __( 'Sharing on facebook requires that you have a Facebook App Id.</br>
						Go to <a href="https://developers.facebook.com/" target="_blank"> https://developers.facebook.com/ </a></br>
						Log in using your existing Facebook account or create a new one.</br>
						Under "My Apps" in the header, select "Add a New App". </br>
						Select "website". </br>
						Choose a name for your new app. </br>
						Skip the quick set-up and go to your app\'s dashboard. The App ID will be at the top under your app\'s name. </br>
						Under "Settings", fill in the fields for "Namespace", "Contact Email", and "Site URL". Facebook sharing will not work unless the Site URL matches the url of your Wordpress site. </br>
						Finally, go to "Status & Review" and click the button to make your app public. </br>' ),
					'type'			=> 'tex',
					'default'		=> ''
				),

				array(
					'id' 			=> 'doc_3',
					'label'			=> __( 'Facebook Settings App Token', 'social-share-with-floating-bar' ),
					'description'	=> __( 'Sharing on facebook requires that you have a Facebook App Token.</br>
						Getting Facebook share counts from the Facebook Graph API requires an "App Token". After setting up an app following the previous steps, go to <a href="https://developers.facebook.com/tools/accesstoken/" target="_blank">https://developers.facebook.com/tools/accesstoken/</a> </br>
						find your App Token under your app. Copy and paste here in the plugin settings.</br>
						' ),
					'type'			=> 'tex',
					'default'		=> ''
				),
			) 
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = sanitize_text_field( wp_unslash( $_POST['tab'] ) );
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) continue;

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}

				if ( ! $current_section ) break;
			}
		}
	}

	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
		$html .= '<h2 class="plugin-settings-title">' . __( 'Social Share with Floating Bar Settings' , 'social-share-with-floating-bar' ) . '</h2>' . "\n";

		$tab = '';
		if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
			$tab .= sanitize_text_field( wp_unslash( $_GET['tab'] ) );
		}

		// Show page tabs
		if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

			$html .= '<h2 class="nav-tab-wrapper">' . "\n";

			$c = 0;
			foreach ( $this->settings as $section => $data ) {

				// Set tab class
				$class = 'nav-tab';
				if ( ! isset( $_GET['tab'] ) ) {
					if ( 0 == $c ) {
						$class .= ' nav-tab-active';
					}
				} else {
					if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
						$class .= ' nav-tab-active';
					}
				}

				// Set tab link
				$tab_link = add_query_arg( array( 'tab' => $section ) );
				if ( isset( $_GET['settings-updated'] ) ) {
					$tab_link = remove_query_arg( 'settings-updated', $tab_link );
				}

				// Output tab
				$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

				++$c;
			}

			$html .= '</h2>' . "\n";
		}

		$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

			// Get settings fields
			ob_start();
			settings_fields( $this->parent->_token . '_settings' );
			do_settings_sections( $this->parent->_token . '_settings' );
			$html .= ob_get_clean();

			$html .= '<p class="submit">' . "\n";
				$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
				$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'social-share-with-floating-bar' ) ) . '" />' . "\n";
			$html .= '</p>' . "\n";
		$html .= '</form>' . "\n";

		ob_start();

		$html .= ob_get_clean();

		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * Main Social_Share_With_Floating_Bar_Settings Instance
	 *
	 * Ensures only one instance of Social_Share_With_Floating_Bar_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Social_Share_With_Floating_Bar()
	 * @return Main Social_Share_With_Floating_Bar_Settings instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}
