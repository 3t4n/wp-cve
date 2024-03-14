<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    sb_bar
 * @subpackage sb_bar/admin
 */
class sb_bar_Enable_Settings extends sb_bar_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $sb_bar    The ID of this plugin.
	 */
	private $sb_bar;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $sb_bar       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $sb_bar ) {

		$this->label = __( 'Enable/Disable', 'sb_bar' );
		$this->sb_bar = $sb_bar.'_enable';
		$this->plugin_settings_tabs[$this->sb_bar] = $this->label;
	}

	/**
	 * Creates our settings sections with fields etc. 
	 *
	 * @since    1.0.0
	 */
	public function settings_api_init(){

		// register_setting( $option_group, $option_name, $settings_sanitize_callback );
		register_setting(
			$this->sb_bar . '_options',
			$this->sb_bar . '_options',
			array( $this, 'settings_sanitize' )
		);

		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->sb_bar . '-options', // section
			apply_filters( $this->sb_bar . '-display-section-title', __( '', $this->sb_bar ) ),
			array( $this, 'display_options_section' ),
			$this->sb_bar . '-enable'
		);

		add_settings_field(
			'disable-author',
			apply_filters( $this->sb_bar . '-disable-author', __( 'Disable Author', $this->sb_bar ) ),
			array( $this, 'disable_author' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-options'
		);
		add_settings_field(
			'disable-ttr',
			apply_filters( $this->sb_bar . '-disable-ttr', __( 'Disable Time to Read Text', $this->sb_bar ) ),
			array( $this, 'disable_ttr' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-options'
		);
		add_settings_field(
			'disable-comments',
			apply_filters( $this->sb_bar . '-disable-comments', __( 'Disable Comments', $this->sb_bar ) ),
			array( $this, 'disable_comments' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-options'
		);
		add_settings_field(
			'old-share-style',
			apply_filters( $this->sb_bar . '-old-share-style', __( 'Use old (square) share buttons', $this->sb_bar ) ),
			array( $this, 'old_share_style' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-options'
		);
		add_settings_field(
			'disable-share',
			apply_filters( $this->sb_bar . '-disable-share', __( 'Disable Share', $this->sb_bar ) ),
			array( $this, 'disable_share' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-options'
		);
		add_settings_field(
			'disable-share-count',
			apply_filters( $this->sb_bar . '-disable-share-count', __( 'Disable Share Count', $this->sb_bar ) ),
			array( $this, 'disable_share_count' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-options'
		);
		add_settings_field(
			'disable-pinterest',
			apply_filters( $this->sb_bar . '-disable-pinterest', __( 'Disable Pinterest Button', $this->sb_bar ) ),
			array( $this, 'disable_pinterest' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-options'
		);
		add_settings_field(
			'disable-linkedin',
			apply_filters( $this->sb_bar . '-disable-linkedin', __( 'Disable LinkedIn Button', $this->sb_bar ) ),
			array( $this, 'disable_linkedin' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-options'
		);
		add_settings_field(
			'disable-googleplus',
			apply_filters( $this->sb_bar . '-disable-googleplus', __( 'Disable Google Plus Button', $this->sb_bar ) ),
			array( $this, 'disable_googleplus' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-options'
		);
		add_settings_field(
			'disable-twitter',
			apply_filters( $this->sb_bar . '-disable-twitter', __( 'Disable Twitter Button', $this->sb_bar ) ),
			array( $this, 'disable_twitter' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-options'
		);
		add_settings_field(
			'disable-facebook',
			apply_filters( $this->sb_bar . '-disable-facebook', __( 'Disable Facebook Button (Why would you do that?)', $this->sb_bar ) ),
			array( $this, 'disable_facebook' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-options'
		);
	}

	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function display_options_section( $params ) {

		echo '<p>' . $params['title'] . '</p>';

	} // display_options_section()


	/**
	 * Disable Author Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_author() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-author'] ) ) {
			$option = $options['disable-author'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-author]" name="<?php echo $this->sb_bar; ?>_options[disable-author]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_author()

	/**
	 * Disable TTR Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_ttr() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-ttr'] ) ) {
			$option = $options['disable-ttr'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-ttr]" name="<?php echo $this->sb_bar; ?>_options[disable-ttr]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_ttr()

	/**
	 * Disable Share Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_share() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-share'] ) ) {
			$option = $options['disable-share'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-share]" name="<?php echo $this->sb_bar; ?>_options[disable-share]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_share()

	/**
	 * Disable Share Count
	 *
	 * @since 		1.2.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_share_count() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-share-count'] ) ) {
			$option = $options['disable-share-count'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-share-count]" name="<?php echo $this->sb_bar; ?>_options[disable-share-count]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_share()

	/**
	 * Disable Comments Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_comments() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-comments'] ) ) {
			$option = $options['disable-comments'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-comments]" name="<?php echo $this->sb_bar; ?>_options[disable-comments]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_comments()

	/**
	 * Use old share style
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function old_share_style() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['old-share-style'] ) ) {
			$option = $options['old-share-style'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[old-share-style]" name="<?php echo $this->sb_bar; ?>_options[old-share-style]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_comments()

	/**
	 * Disable Pinterest Button
	 *
	 * @since 		1.1.1
	 * @return 		mixed 			The settings field
	 */
	public function disable_pinterest() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-pinterest'] ) ) {
			$option = $options['disable-pinterest'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-pinterest]" name="<?php echo $this->sb_bar; ?>_options[disable-pinterest]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_pinterest()

	/**
	 * Disable Linkedin Button
	 *
	 * @since 		1.1.1
	 * @return 		mixed 			The settings field
	 */
	public function disable_linkedin() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-linkedin'] ) ) {
			$option = $options['disable-linkedin'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-linkedin]" name="<?php echo $this->sb_bar; ?>_options[disable-linkedin]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_linkedin()

	/**
	 * Disable Google Plus
	 *
	 * @since 		1.1.1
	 * @return 		mixed 			The settings field
	 */
	public function disable_googleplus() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-googleplus'] ) ) {
			$option = $options['disable-googleplus'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-googleplus]" name="<?php echo $this->sb_bar; ?>_options[disable-googleplus]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_googleplus()

	/**
	 * Disable Twitter
	 *
	 * @since 		1.1.1
	 * @return 		mixed 			The settings field
	 */
	public function disable_twitter() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-twitter'] ) ) {
			$option = $options['disable-twitter'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-twitter]" name="<?php echo $this->sb_bar; ?>_options[disable-twitter]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_twitter()

	/**
	 * Disable Facebook
	 *
	 * @since 		1.1.1
	 * @return 		mixed 			The settings field
	 */
	public function disable_facebook() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-facebook'] ) ) {
			$option = $options['disable-facebook'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-facebook]" name="<?php echo $this->sb_bar; ?>_options[disable-facebook]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_facebook()
}
