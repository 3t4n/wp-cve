<?php

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 24 May 2011
 *
 * @file WordpressConnectAdminPanelLikeBox.php
 *
 * This class provides functionality for the wordpress dashboard admin
 * panel for the Wordpress Connect Like Box facebook plugin
 */
class WordpressConnectAdminPanelLikeBox {


	/**
	 * Creates a new instance of WordpressConnectAdminPanelLikeBox
	 *
	 * @since	2.0
	 *
	 */
	function WordpressConnectAdminPanelLikeBox(){

		add_action( 'admin_init', array( &$this, 'add_admin_settings' ), 9 );
		add_action( 'admin_menu', array( &$this, 'add_admin_panel' ) );

	}

	/**
	 * Adds plugin's admin panel to the wp dashboard
	 *
	 * @private
	 * @since	2.0
	 */
	function add_admin_settings(){

		if ( !current_user_can( 'manage_options' ) ) { return;	}		
		
		register_setting( WPC_OPTIONS_LIKE_BOX, WPC_OPTIONS_LIKE_BOX, array( &$this, 'admin_like_box_settings_validate' ) );

		// adds sections
		add_settings_section( WPC_SETTINGS_SECTION_LIKE_BOX, __( 'Like Box Options', WPC_TEXT_DOMAIN ), array( &$this, 'admin_section_like_box' ), WPC_SETTINGS_LIKE_BOX_PAGE );

		// like box settings
		add_settings_field( WPC_OPTIONS_LIKE_BOX_URL, __( 'Like Box URL', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_box_url' ), WPC_SETTINGS_LIKE_BOX_PAGE, WPC_SETTINGS_SECTION_LIKE_BOX );

	}

	/**
	 * Validates like box settings
	 * @param	$input the settings value
	 */
	function admin_like_box_settings_validate( $input ){

		$input = apply_filters( WPC_OPTIONS_LIKE_BOX, $input ); // filter to let sub-plugins validate their options too
		return $input;
	}

	/**
	 */
	function admin_section_like_box(){}

	/**
	 * Renders the like box url field
	 */
	function admin_setting_like_box_url(){

		$options = get_option( WPC_OPTIONS_LIKE_BOX );
		echo '<input type="text" id="',WPC_OPTIONS_LIKE_BOX_URL,'" name="',WPC_OPTIONS_LIKE_BOX,'[',WPC_OPTIONS_LIKE_BOX_URL.']" value="'. $options[ WPC_OPTIONS_LIKE_BOX_URL ] .'" size="96" />';
		echo '<br /><span class="description">';
		_e( "If you current theme displays the Facebook Like Box anywhere on the theme pages (i.e. a Like Button you did not add as a widget) this url specifies the url to the Facebook Page to which the Like Box will point.", WPC_TEXT_DOMAIN );
		echo '</span>';

	}

	/**
	 * Adds plugin's admin panel to the wp dashboard
	 *
	 * @private
	 * @since	2.0
	 */
	function add_admin_panel(){

		global $wpc_like_box_manage_page;

		$wpc_like_box_manage_page = add_submenu_page(
			WPC_SETTINGS_PAGE,
			__( 'Like Box', WPC_TEXT_DOMAIN ),
			__( 'Like Box', WPC_TEXT_DOMAIN ),
			'manage_options',
			WPC_SETTINGS_LIKE_BOX_PAGE,
			array( &$this, 'admin_section_like_box_page' )
		);
	}

	/**
	 *
	 */
	function admin_section_like_box_page(){

?>
		<div class="wrap" style="width:70%">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2><?php _e('Facebook Like Box Settings', WPC_TEXT_DOMAIN ) ?></h2>
			<form method="post" action="options.php">
			<?php settings_fields( WPC_OPTIONS_LIKE_BOX ); ?>
				<table><tr><td>
				<?php do_settings_sections( WPC_SETTINGS_LIKE_BOX_PAGE ); ?>
				</td></tr></table>
				<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
				</p>
			</form>
		</div>
<?php

	}



	/**
	 * Restores default configuration
	 */
	public static function restoreDefaults(){

		// set the settings controlled by this class to their default values
	}
}

?>