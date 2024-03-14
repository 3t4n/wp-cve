<?php class EModal_Admin {

	public static $Updaters = array();

	public function __construct() {

		global $EModal_License, $EModal_Admin_Menu, $EModal_Admin_Editor, $EModal_Admin_Postmeta;
		$EModal_Admin_Menu     = new EModal_Admin_Menu;
		$EModal_Admin_Editor   = new EModal_Admin_Editor;
		$EModal_Admin_Postmeta = new EModal_Admin_Postmeta;
		$EModal_License        = new EModal_License;
		if ( emodal_get_option( 'EasyModal_Version' ) && emodal_get_option( EMCORE_SLUG . '_migration_approved' ) ) {
			EModal_Migrate_Pre_V2::delete_all();
		}
		add_filter( 'plugin_action_links', array( $this, 'action_links' ), 10, 2 );

		add_action( "init", array( $this, 'update_transient_plugin_slugs' ) );

		//add_filter ('pre_set_site_transient_update_plugins', array(&$this, 'transient_update_plugins'));
		//add_filter ('pre_set_transient_update_plugins', array(&$this, 'transient_update_plugins'));

		add_action( 'admin_init', array( $this, 'reset_emodal_db' ) );

		add_action( 'admin_notices', array( $this, 'popup_maker_notice' ) );
		add_action( 'admin_init', array( $this, 'dismiss_popup_maker_notice' ) );


		if ( is_emodal_admin_page() ) {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_head', array( $this, 'admin_head' ) );
			add_action( "admin_enqueue_scripts", array( $this, 'styles' ) );
			add_action( "admin_enqueue_scripts", array( $this, 'scripts' ) );
			add_action( 'admin_print_footer_scripts', array( $this, 'admin_footer' ), 1000 );
		}
	}

	public static function check_updates( $slug = null ) {
		if ( $slug !== null && ! empty( self::$Updaters[ $slug ] ) ) {
			self::$Updaters[ $slug ]->checkForUpdates();
		} elseif ( ! $slug ) {
			foreach ( self::$Updaters as $slug => $Updater ) {
				$Updater->checkForUpdates();
			}
		}
	}

	public function transient_update_plugins( $transient ) {
		$addons = EModal_License::available_addons();
		if ( ! empty( $addons ) ) {
			foreach ( $addons as $addon ) {
				$obj                                                              = new stdClass();
				$obj->slug                                                        = $addon->slug . '.php';
				$obj->new_version                                                 = ! empty( $addon->version ) ? $addon->version : $addon->new_version;
				$obj->url                                                         = $addon->homepage;
				$obj->package                                                     = ! empty( $addon->download_url ) ? $addon->download_url : null;
				$transient->checked[ $addon->slug . '/' . $addon->slug . '.php' ] = $obj->new_version;
				if ( version_compare( $transient->checked[ $addon->slug . '/' . $addon->slug . '.php' ], $obj->new_version ) == - 1 ) {
					$transient->response[ $addon->slug . '/' . $addon->slug . '.php' ] = $obj;
				}
			}
		}

		return $transient;
	}

	public function update_transient_plugin_slugs() {
		$transient = get_option( '_transient_plugin_slugs' );
		if ( $transient ) {
			$save   = false;
			$addons = EModal_License::available_addons();
			if ( ! empty( $addons ) ) {
				foreach ( $addons as $addon ) {
					if ( ! in_array( $addon->slug . '/' . $addon->slug . '.php', $transient ) ) {
						$transient[] = $addon->slug . '/' . $addon->slug . '.php';
						$save        = true;
					}
				}
			}
			if ( $save ) {
				update_option( '_transient_plugin_slugs', $transient );
			}
		}
	}

	public function admin_init() {
		call_user_func( array(
			apply_filters( 'emodal_admin_current_controller', 'EModal_Controller_Admin_Modals' ),
			'factory',
		) );
	}

	public function admin_head() {
		if ( ! function_exists( 'wp_editor' ) ) {
			wp_tiny_mce();
		}
	}

	public function admin_footer() {
		do_action( 'emodal_admin_footer' );
	}

	public function styles() {
		wp_enqueue_style( EMCORE_SLUG . '-admin', EMCORE_URL . '/assets/styles/' . EMCORE_SLUG . '-admin.css', false, 0.1 );
	}

	public function scripts() {
		if ( $_GET['page'] == emodal_admin_slug() ) {
			wp_enqueue_script( 'word-count' );
			wp_enqueue_script( 'post' );
			wp_enqueue_script( 'editor' );
			wp_enqueue_script( 'media-upload' );
		} elseif ( $_GET['page'] == emodal_admin_slug( 'themes' ) ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'jquery-ui-slider' );
		}
		wp_enqueue_script( EMCORE_SLUG . '-admin', EMCORE_URL . '/assets/scripts/' . EMCORE_SLUG . '-admin.js', array(
			'jquery',
			'wp-color-picker',
			'jquery-ui-slider'
			/*, 'jquery-ui-core', 'jquery-ui-slider', 'jquery-colorpicker'*/
		) );
	}

	public function action_links( $links, $file ) {
		if ( $file == plugin_basename( EMCORE ) ) {
			foreach (
				apply_filters( 'emodal_action_links', array(
					'settings' => '<a href="' . emodal_admin_url( 'settings' ) . '">' . __( 'Settings', 'easy-modal' ) . '</a>',
					'gopro'    => '<a href="https://easy-modal.com/pricing?utm_source=em-free&utm_medium=plugins+page&utm_campaign=go+pro" target="_blank">' . __( 'Go Pro', 'easy-modal' ) . '</a>',
				) ) as $link
			) {
				array_unshift( $links, $link );
			}
		}

		return $links;
	}

	public function reset_emodal_db() {


		if ( isset( $_POST['remove_old_emodal_data'] ) ) {

			// run a quick security check 
			if ( ! check_admin_referer( EMCORE_NONCE, EMCORE_NONCE ) ) {
				return;
			} // get out if we didn't click the Activate button

			global $wpdb;

			$wpdb->query( "DELETE FROM $wpdb->options WHERE `option_name` LIKE 'EasyModal%';" );

			do_action( 'remove_old_emodal_data' );

		}


		if ( isset( $_POST['reset_emodal_db'] ) ) {

			// run a quick security check 
			if ( ! check_admin_referer( EMCORE_NONCE, EMCORE_NONCE ) ) {
				return;
			} // get out if we didn't click the Activate button

			global $wpdb;
			$wpdb->query( "DROP TABLE IF EXISTS `$wpdb->em_modal_metas`;" );
			$wpdb->query( "DROP TABLE IF EXISTS `$wpdb->em_modals`;" );
			$wpdb->query( "DROP TABLE IF EXISTS `$wpdb->em_theme_metas`;" );
			$wpdb->query( "DROP TABLE IF EXISTS `$wpdb->em_themes`;" );

			emodal_delete_option( EMCORE_SLUG . '_db_version' );

			do_action( 'emodal_db_update', false );

			/*
			$wpdb->query( "DELETE FROM $wpdb->em_modal_metas" );
			$wpdb->query( "DELETE FROM $wpdb->em_modals" );
			$wpdb->query( "DELETE FROM $wpdb->em_theme_metas" );
			$wpdb->query( "DELETE FROM $wpdb->em_themes" );
			*/
			do_action( 'emodal_reset_db' );

		}


		if ( isset( $_POST['migrate_emodal_db'] ) ) {

			// run a quick security check 
			if ( ! check_admin_referer( EMCORE_NONCE, EMCORE_NONCE ) ) {
				return;
			} // get out if we didn't click the Activate button

			if ( emodal_get_option( 'EasyModal_Version' ) ) {
				global $wpdb;
				$wpdb->query( "DELETE FROM $wpdb->em_modal_metas" );
				$wpdb->query( "DELETE FROM $wpdb->em_modals" );
				$wpdb->query( "DELETE FROM $wpdb->em_theme_metas" );
				$wpdb->query( "DELETE FROM $wpdb->em_themes" );
				new EModal_Migrate_Pre_V2;

				do_action( 'emodal_migrate_db' );
			}

		}


		if ( isset( $_POST['uninstall_emodal_db'] ) ) {

			// run a quick security check 
			if ( ! check_admin_referer( EMCORE_NONCE, EMCORE_NONCE ) ) {
				return;
			} // get out if we didn't click the Activate button

			global $wpdb;
			$wpdb->query( "DROP TABLE IF EXISTS `$wpdb->em_modal_metas`;" );
			$wpdb->query( "DROP TABLE IF EXISTS `$wpdb->em_modals`;" );
			$wpdb->query( "DROP TABLE IF EXISTS `$wpdb->em_theme_metas`;" );
			$wpdb->query( "DROP TABLE IF EXISTS `$wpdb->em_themes`;" );

			$wpdb->query( "DELETE FROM $wpdb->options WHERE `option_name` LIKE 'easy-modal%';" );

			do_action( 'emodal_uninstall' );

			emodal_update_option( EMCORE_SLUG . '_uninstalled', true );
		}
	}

	public function popup_maker_notice() {
		$user_id = get_current_user_id();
		if ( ! get_user_meta( $user_id, 'emodal_popup_maker_notice_dismissed' ) ) {
			$url  = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=popup-maker' ), 'install-plugin_popup-maker' ) );
			?>
			<div class="notice notice-success" style="position: relative;">
				<a style="text-decoration: none;" href="?emodal-dismiss-popup-maker-notice" class="notice-dismiss">
					<span class="screen-reader-text"><?php _e( 'Dismiss this notice.' ); ?></span>
				</a>
				<p>Did you know, that Easy Modal has a fancy new replacement called <strong><a href="https://wordpress.org/plugins/popup-maker/" target="_blank">Popup Maker</a></strong>? It is the highest user rated popup & modal plugin available for WordPress.</p>
				<ul class="ul-square">
					<li>Unlimited themes</li>
					<li>Precision Targeting, Triggers & Cookies</li>
					<li>Customize everything</li>
					<li>Full line of extensions</li>
					<li>Extensive Documentation & Developer APIs</li>
					<li><a href="https://wordpress.org/plugins/popup-maker/" target="_blank">Learn more</a> or <a href="<?php echo esc_url( $url ); ?>">Install it now!</a></li>
				</ul>
			</div>

			<?php
		}
	}

	public function dismiss_popup_maker_notice() {
		$user_id = get_current_user_id();
		if ( isset( $_GET['emodal-dismiss-popup-maker-notice'] ) ) {
			add_user_meta( $user_id, 'emodal_popup_maker_notice_dismissed', 'true', true );
		}
	}

}

add_filter( 'emodal_admin_current_controller', 'emodal_admin_current_controller', 1 );
function emodal_admin_current_controller( $controller ) {
	switch ( $_GET['page'] ) {
		case emodal_admin_slug( 'themes' ):
			$controller = 'EModal_Controller_Admin_Theme';
			break;
		case emodal_admin_slug( 'settings' ):
			$controller = 'EModal_Controller_Admin_Settings';
			break;
		case emodal_admin_slug( 'addons' ):
			$controller = 'EModal_Controller_Admin_Addons';
			break;
		case emodal_admin_slug( 'help' ):
			$controller = 'EModal_Controller_Admin_Help';
			break;
	}

	return $controller;
}