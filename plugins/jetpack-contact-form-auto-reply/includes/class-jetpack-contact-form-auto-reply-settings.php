<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Jetpack_Contact_Form_Auto_Reply_Settings {

	/**
	 * The single instance of Jetpack_Contact_Form_Auto_Reply_Settings.
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

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'jetpack_auto_reply_';

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ), 999 );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item () {
		add_submenu_page( 'jetpack', __( 'Jetpack Contact Form Auto Reply Settings', 'jetpack-contact-form-auto-reply' ), __( 'Auto Reply', 'jetpack-contact-form-auto-reply' ), 'jetpack_manage_modules', 'jetpack_auto_reply_settings', array( $this , 'settings_page' ) );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="admin.php?page=jetpack_auto_reply_settings">' . __( 'Settings', 'jetpack-contact-form-auto-reply' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {

		add_settings_section( 'general-settings', '', array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

		register_setting( $this->parent->_token . '_settings', $this->base . 'enable' );
		register_setting( $this->parent->_token . '_settings', $this->base . 'email_subject' );
		register_setting( $this->parent->_token . '_settings', $this->base . 'email_content' );
		register_setting( $this->parent->_token . '_settings', $this->base . 'email_from_name' );
		register_setting( $this->parent->_token . '_settings', $this->base . 'email_from_address' );
		register_setting( $this->parent->_token . '_settings', $this->base . 'email_field' );
		register_setting( $this->parent->_token . '_settings', $this->base . 'not_spam' );

		add_settings_field( 'enable', __( 'Enable auto reply', 'jetpack-contact-form-auto-reply' ), array( $this, 'enable_field' ), $this->parent->_token . '_settings', 'general-settings' );
		add_settings_field( 'email_subject', __( 'Email subject', 'jetpack-contact-form-auto-reply' ), array( $this, 'email_subject' ), $this->parent->_token . '_settings', 'general-settings' );
		add_settings_field( 'email_content', __( 'Email content', 'jetpack-contact-form-auto-reply' ), array( $this, 'content_field' ), $this->parent->_token . '_settings', 'general-settings' );
		add_settings_field( 'email_from_name', __( 'Email from name', 'jetpack-contact-form-auto-reply' ), array( $this, 'email_from_name' ), $this->parent->_token . '_settings', 'general-settings' );
		add_settings_field( 'email_from_address', __( 'Email from address', 'jetpack-contact-form-auto-reply' ), array( $this, 'email_from_address' ), $this->parent->_token . '_settings', 'general-settings' );
		add_settings_field( 'email_field', __( 'Email address field', 'jetpack-contact-form-auto-reply' ), array( $this, 'email_field' ), $this->parent->_token . '_settings', 'general-settings' );
		add_settings_field( 'not_spam', __( 'Check for spam', 'jetpack-contact-form-auto-reply' ), array( $this, 'not_spam_field' ), $this->parent->_token . '_settings', 'general-settings' );

	}

	public function settings_section ( $section ) {}

	public function enable_field () {

		$enable = get_option( $this->base . 'enable', '' );

		$html = '<input id="' . esc_attr( $this->base . 'enable' ) . '" type="checkbox" value="on" name="' . esc_attr( $this->base . 'enable' ) . '" ' . checked( $enable, 'on', false ) . ' />' . "\n";

		$html .= '<label for="' . esc_attr( $this->base . 'enable' ) . '">' . "\n";
			$html .= '<span class="description">' . __( 'Enable contact form auto reply.', 'jetpack-contact-form-auto-reply' ) . '</span>' . "\n";
		$html .= '</label>' . "\n";

		echo $html;

	}

	public function email_subject () {

		$email_subject = get_option( $this->base . 'email_subject', '' );

		$html = '<input id="' . esc_attr( $this->base . 'email_subject' ) . '" class="large-text" type="text" placeholder="' . __( 'Email subject', 'jetpack-contact-form-auto-reply' ) . '" name="' . esc_attr( $this->base . 'email_subject' ) . '" value="' . esc_attr( $email_subject ) . '" />' . "\n";

		$html .= '<br/>' . "\n";

		$html .= '<label for="' . esc_attr( $this->base . 'email_subject' ) . '">' . "\n";
			$html .= '<span class="description">' . sprintf( __( 'The subject of the auto reply email. Include sender details by including the field label like this: %1$s{Field name}%2$s - e.g. %1$s{Name}%2$s', 'jetpack-contact-form-auto-reply' ), '<code>', '</code>' ) . '</span>' . "\n";
		$html .= '</label>' . "\n";

		echo $html;
	}

	public function content_field () {

		$email_content = get_option( $this->base . 'email_content', '' );

		$editor_settings = array(
			'textarea_rows' => 15,
			'textarea_name' => $this->base . 'email_content',
			'editor_class' => 'email-content-editor',
			'quicktags' => false,
		);

		ob_start();

		wp_editor( wpautop( $email_content ), 'email-content', $editor_settings );

		$html = ob_get_clean();

		$html .= '<span class="description">' . sprintf( __( 'Include sender message details by including the field label like this: %1$s{Field name}%2$s - e.g. %1$s{Comment}%2$s', 'jetpack-contact-form-auto-reply' ), '<code>', '</code>' ) . '</span>' . "\n";

		echo $html;
	}

	public function email_from_name () {

		$email_from_name = get_option( $this->base . 'email_from_name', get_bloginfo( 'name' ) );

		$html = '<input id="' . esc_attr( $this->base . 'email_from_name' ) . '" class="regular-text" type="text" placeholder="' . __( 'Email from name', 'jetpack-contact-form-auto-reply' ) . '" name="' . esc_attr( $this->base . 'email_from_name' ) . '" value="' . esc_attr( $email_from_name ) . '" />' . "\n";

		$html .= '<br/>' . "\n";

		$html .= '<label for="' . esc_attr( $this->base . 'email_from_name' ) . '">' . "\n";
			$html .= '<span class="description">' . __( 'The sender name on the auto reply email.', 'jetpack-contact-form-auto-reply' ) . '</span>' . "\n";
		$html .= '</label>' . "\n";

		echo $html;
	}

	public function email_from_address () {

		$email_from_address = get_option( $this->base . 'email_from_address', get_bloginfo( 'admin_email' ) );

		$html = '<input id="' . esc_attr( $this->base . 'email_from_address' ) . '" class="regular-text" type="email" placeholder="' . __( 'Email from address', 'jetpack-contact-form-auto-reply' ) . '" name="' . esc_attr( $this->base . 'email_from_address' ) . '" value="' . esc_attr( $email_from_address ) . '" />' . "\n";

		$html .= '<br/>' . "\n";

		$html .= '<label for="' . esc_attr( $this->base . 'email_from_address' ) . '">' . "\n";
			$html .= '<span class="description">' . __( 'The sender address on the auto reply email.', 'jetpack-contact-form-auto-reply' ) . '</span>' . "\n";
		$html .= '</label>' . "\n";

		echo $html;
	}

	public function email_field () {

		$email_field = get_option( $this->base . 'email_field', '' );

		$html = '<input id="' . esc_attr( $this->base . 'email_field' ) . '" class="regular-text" type="text" placeholder="' . __( 'Field label', 'jetpack-contact-form-auto-reply' ) . '" name="' . esc_attr( $this->base . 'email_field' ) . '" value="' . esc_attr( $email_field ) . '" />' . "\n";

		$html .= '<br/>' . "\n";

		$html .= '<label for="' . esc_attr( $this->base . 'email_field' ) . '">' . "\n";
			$html .= '<span class="description">' . __( 'The label of the field in the contact form that will contain the email address to use for the auto reply (case sensitive) - e.g. \'Email\'.', 'jetpack-contact-form-auto-reply' ) . '</span>' . "\n";
		$html .= '</label>' . "\n";

		echo $html;
	}

	public function not_spam_field () {

		$not_spam = get_option( $this->base . 'not_spam', '' );

		$html = '<input id="' . esc_attr( $this->base . 'not_spam' ) . '" type="checkbox" value="on" name="' . esc_attr( $this->base . 'not_spam' ) . '" ' . checked( $not_spam, 'on', false ) . ' />' . "\n";

		$html .= '<label for="' . esc_attr( $this->base . 'not_spam' ) . '">' . "\n";
			$html .= '<span class="description">' . __( 'Do not send auto reply if email is marked as spam.', 'jetpack-contact-form-auto-reply' ) . '</span>' . "\n";
		$html .= '</label>' . "\n";

		echo $html;

	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
			$html .= '<h2>' . __( 'Settings' , 'jetpack-contact-form-auto-reply' ) . '</h2>' . "\n";

			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

				// Get settings fields
				ob_start();
				settings_fields( $this->parent->_token . '_settings' );
				do_settings_sections( $this->parent->_token . '_settings' );
				$html .= ob_get_clean();

				$html .= '<p class="submit">' . "\n";
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'jetpack-contact-form-auto-reply' ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * Display admin notices int he dashboard
	 * @return void
	 */
	public function admin_notices () {

		if( isset( $_GET['page'] ) && 'jetpack_auto_reply_settings' == $_GET['page'] && isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) {
			?>
		    <div class="updated">
		        <p><?php _e( 'Settings updated.', 'jetpack-contact-form-auto-reply' ); ?></p>
		    </div>
		    <?php
		}

	} // End admin_notices ()

	/**
	 * Main Jetpack_Contact_Form_Auto_Reply_Settings Instance
	 *
	 * Ensures only one instance of Jetpack_Contact_Form_Auto_Reply_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Jetpack_Contact_Form_Auto_Reply()
	 * @return Main Jetpack_Contact_Form_Auto_Reply_Settings instance
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
