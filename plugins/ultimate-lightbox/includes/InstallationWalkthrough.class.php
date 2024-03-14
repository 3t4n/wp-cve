<?php

/**
 * Class to handle everything related to the walk-through that runs on plugin activation
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

class ewdulbInstallationWalkthrough {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_install_screen' ) );
		add_action( 'admin_head', array( $this, 'hide_install_screen_menu_item' ) );
		add_action( 'admin_init', array( $this, 'redirect' ), 9999 );

		add_action( 'admin_head', array( $this, 'admin_enqueue' ) );

		add_action( 'wp_ajax_ulb_welcome_set_options', array( $this, 'set_options' ) );
	}

	public function redirect() {
		if ( ! get_transient( 'ulb-getting-started' ) ) 
			return;

		delete_transient( 'ulb-getting-started' );

		if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
			return;

		if ( get_option( 'ulb-settings') ) {
			return;
		}

		wp_safe_redirect( admin_url( 'index.php?page=ulb-getting-started' ) );
		exit;
	}

	public function register_install_screen() {

		add_dashboard_page(
			esc_html__( 'Ultimate Lightbox - Welcome!', 'ultimate-lightbox' ),
			esc_html__( 'Ultimate Lightbox - Welcome!', 'ultimate-lightbox' ),
			'manage_options',
			'ulb-getting-started',
			array($this, 'display_install_screen')
		);
	}

	public function hide_install_screen_menu_item() {

		remove_submenu_page( 'index.php', 'ulb-getting-started' );
	}

	public function set_options() {

		// Authenticate request
		if ( ! check_ajax_referer( 'ewd-ulb-getting-started', 'nonce' ) ) {
			
			ewdulbHelper::admin_nopriv_ajax();
		}

		$ulb_options = get_option( 'ulb-settings' );

		if ( isset( $_POST['add_lightbox'] ) ) { 

			$add_lightbox = is_array( json_decode( stripslashes( $_POST['add_lightbox'] ) ) ) ? json_decode( stripslashes( $_POST['add_lightbox'] ) ) : array();
			$ulb_options['add-lightbox'] = array_map( 'sanitize_text_field', $add_lightbox ); 
		}

		if ( isset( $_POST['image_class_list'] ) ) { $ulb_options['image-class-list'] = sanitize_text_field( $_POST['image_class_list'] ); }
		if ( isset( $_POST['image_selector_list'] ) ) { $ulb_options['image-selector-list'] = sanitize_text_field( $_POST['image_selector_list'] ); }

		if ( isset( $_POST['show_thumbnails'] ) ) { $ulb_options['show-thumbnails'] = sanitize_text_field( $_POST['show_thumbnails'] ); }
		if ( isset( $_POST['show_overlay_text'] ) ) { $ulb_options['show-overlay-text'] = $_POST['show_overlay_text'] == 'true' ? 1 : 0; }
		if ( isset( $_POST['background_close'] ) ) { $ulb_options['background-close'] = $_POST['background_close'] == 'true' ? 1 : 0; }

		if ( isset( $_POST['mobile_hide_elements'] ) ) { 
			
			$mobile_hide_elements = is_array( json_decode( stripslashes( $_POST['mobile_hide_elements'] ) ) ) ? json_decode( stripslashes( $_POST['mobile_hide_elements'] ) ) : array();
			$ulb_options['mobile-hide-elements'] = array_map( 'sanitize_text_field', $mobile_hide_elements ); 
		}

		if ( isset( $_POST['arrow'] ) ) { $ulb_options['arrow'] = sanitize_text_field( $_POST['arrow'] ); }
		if ( isset( $_POST['icons'] ) ) { $ulb_options['icon-set'] = sanitize_text_field( $_POST['icons'] ); }

		update_option( 'ulb-settings', $ulb_options );
	
	    exit();
	}

	public function admin_enqueue() {

		if ( ! isset( $_GET['page'] ) or $_GET['page'] != 'ulb-getting-started' ) { return; }

		wp_enqueue_style( 'ulb-admin-css', EWD_ULB_PLUGIN_URL . '/assets/css/ewd-ulb-admin.css', array(), EWD_ULB_VERSION );
		wp_enqueue_style( 'ulb-sap-admin-css', EWD_ULB_PLUGIN_URL . '/lib/simple-admin-pages/css/admin.css', array(), EWD_ULB_VERSION );
		wp_enqueue_style( 'ulb-welcome-screen', EWD_ULB_PLUGIN_URL . '/assets/css/admin-ulb-welcome-screen.css', array(), EWD_ULB_VERSION );
		wp_enqueue_style( 'ulb-admin-settings-css', EWD_ULB_PLUGIN_URL . '/lib/simple-admin-pages/css/admin-settings.css', array(), EWD_ULB_VERSION );
		
		wp_enqueue_script( 'ulb-getting-started', EWD_ULB_PLUGIN_URL . '/assets/js/admin-ulb-welcome-screen.js', array( 'jquery' ), EWD_ULB_VERSION );
		wp_enqueue_script( 'ulb-admin-settings-js	', EWD_ULB_PLUGIN_URL . '/lib/simple-admin-pages/js/admin-settings.js', array( 'jquery' ), EWD_ULB_VERSION );

		wp_localize_script(
			'ulb-getting-started',
			'ewd_ulb_getting_started',
			array(
				'nonce' => wp_create_nonce( 'ewd-ulb-getting-started' )
			)
		);
	}

	public function display_install_screen() { ?>

		<div class='ulb-welcome-screen'>
			
			<div class='ulb-welcome-screen-header'>
				<h1><?php _e( 'Welcome to the Ultimate Lightbox Plugin', 'ultimate-lightbox' ); ?></h1>
				<p><?php _e( 'Thanks for choosing the Ultimate Lightbox! The following will help you get started with the plugin, by choosing which images the lightbox should be displayed for as well as the look of the lightbox.', 'ultimate-lightbox' ); ?></p>
			</div>

			<div class='ulb-welcome-screen-box ulb-welcome-screen-add_lightbox ulb-welcome-screen-open' data-screen='add_lightbox'>
				<h2><?php _e( '1. Add Lightboxes', 'ultimate-lightbox' ); ?></h2>
				<div class='ulb-welcome-screen-box-content'>
					<table class='form-table ulb-welcome-screen-table'>
						<tr>
							<th scope='row'><?php _e( 'Images with Lightbox', 'ultimate-lightbox'); ?></th>
							<td class='ulb-welcome-screen-option'>
								<fieldset>
									<label title='All Images' class='sap-admin-input-container'><input type='checkbox' name='add_lightbox[]' value='all_images'><span class='sap-admin-checkbox'></span> <span><?php _e( 'All Images', 'ultimate-lightbox' ); ?></span></label>
									<label title='All WordPress Galleries' class='sap-admin-input-container'><input type='checkbox' name='add_lightbox[]' value='galleries'><span class='sap-admin-checkbox'></span> <span><?php _e( 'All WordPress Galleries', 'ultimate-lightbox' ); ?></span></label>
									<label title='All YouTube Videos' class='sap-admin-input-container'><input type='checkbox' name='add_lightbox[]' value='all_youtube'><span class='sap-admin-checkbox'></span> <span><?php _e( 'All YouTube Videos', 'ultimate-lightbox' ); ?></span></label>
									<label title='WooCommerce Product Page Images' class='sap-admin-input-container'><input type='checkbox' name='add_lightbox[]' value='woocommerce_product_page'><span class='sap-admin-checkbox'></span> <span><?php _e( 'WooCommerce Product Page Images', 'ultimate-lightbox' ); ?></span></label>
									<label title='Images with Classes Set Below' class='sap-admin-input-container'><input type='checkbox' name='add_lightbox[]' value='image_class'><span class='sap-admin-checkbox'></span> <span><?php _e( 'Images with Class Set Below', 'ultimate-lightbox' ); ?></span></label>
									<label title='Images with CSS Selectors Set Below' class='sap-admin-input-container'><input type='checkbox' name='add_lightbox[]' value='image_selector'><span class='sap-admin-checkbox'></span> <span><?php _e( 'Images with CSS Selectors Set Below', 'ultimate-lightbox' ); ?></span></label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope='row'><?php _e( 'Classes to Add Lightbox to', 'ultimate-lightbox'); ?></th>
							<td class='ulb-welcome-screen-option'>
								<input type='text' name='image_class_list'>
							</td>
						</tr>
						<tr>
							<th scope='row'><?php _e( 'CSS Selectors to Add Lightbox to', 'ultimate-lightbox'); ?></th>
							<td class='ulb-welcome-screen-option'>
								<input type='text' name='image_selector_list'>
							</td>
						</tr>
					</table>
					<div class='clear'></div>
					<div class='ulb-welcome-screen-save-add-lightbox-button'><?php _e('Save Options', 'ultimate-lightbox'); ?></div>
					<div class="ulb-welcome-clear"></div>
					<div class='ulb-welcome-screen-next-button' data-nextaction='options'><?php _e('Next Step', 'ultimate-lightbox'); ?></div>
					<div class='clear'></div>
				</div>
			</div>

			<div class='ulb-welcome-screen-box ulb-welcome-screen-options' data-screen='options'>
				<h2><?php _e( '2. Key Options', 'ultimate-lightbox' ); ?></h2>
				<div class='ulb-welcome-screen-box-content'>
					<table class='form-table ulb-welcome-screen-table'>
						<tr>
							<th scope='row'><?php _e( 'Show Thumbnail Images', 'ultimate-lightbox'); ?></th>
							<td class='ulb-welcome-screen-option'>
								<fieldset>
									<label title='Top' class='sap-admin-input-container'><input type='radio' name='show_thumbnails' value='top'><span class='sap-admin-radio-button'></span> <span><?php _e( 'Top', 'ultimate-lightbox' )?></span></label><br>		
									<label title='Bottom' class='sap-admin-input-container'><input type='radio' name='show_thumbnails' value='bottom' checked><span class='sap-admin-radio-button'></span> <span><?php _e( 'Bottom', 'ultimate-lightbox' )?></span></label><br>			
									<label title='None' class='sap-admin-input-container'><input type='radio' name='show_thumbnails' value='none'><span class='sap-admin-radio-button'></span> <span><?php _e( 'None', 'ultimate-lightbox' )?></span></label><br>
								</fieldset>
							</td>
						</tr>
						<tr>
						<th scope='row'><?php _e( 'Show Lightbox Image Information', 'ultimate-lightbox'); ?></th>
							<td class='ulb-welcome-screen-option'>
								<fieldset>
									<div class="sap-admin-hide-radios">
										<input type="checkbox" name="show_overlay_text" value="1" checked="checked">
									</div>
									<label class="sap-admin-switch">
										<input type="checkbox" class="sap-admin-option-toggle" data-inputname="show_overlay_text" checked="checked">
										<span class="sap-admin-switch-slider round"></span>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope='row'><?php _e( 'Close on Background Click', 'ultimate-lightbox'); ?></th>
							<td class='ulb-welcome-screen-option'>
								<fieldset>
									<div class="sap-admin-hide-radios">
										<input type="checkbox" name="background_close" value="1" checked="checked">
									</div>
									<label class="sap-admin-switch">
										<input type="checkbox" class="sap-admin-option-toggle" data-inputname="background_close" checked="checked">
										<span class="sap-admin-switch-slider round"></span>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope='row'><?php _e( 'Hide Elements on Mobile', 'ultimate-lightbox'); ?></th>
							<td class='ulb-welcome-screen-option'>
								<fieldset>
									<label title='Title' class='sap-admin-input-container'><input type='checkbox' name='mobile_hide_elements[]' value='title'><span class='sap-admin-checkbox'></span> <span><?php _e( 'Title', 'ultimate-lightbox' ); ?></span></label>
									<label title='Description' class='sap-admin-input-container'><input type='checkbox' name='mobile_hide_elements[]' value='description'><span class='sap-admin-checkbox'></span> <span><?php _e( 'Description', 'ultimate-lightbox' ); ?></span></label>
									<label title='Thumbnails' class='sap-admin-input-container'><input type='checkbox' name='mobile_hide_elements[]' value='thumbnails'><span class='sap-admin-checkbox'></span> <span><?php _e( 'Thumbnails', 'ultimate-lightbox' ); ?></span></label>
								</fieldset>
							</td>
						</tr>
					</table>

					<div class='ulb-welcome-screen-save-options-button'><?php _e('Save Options', 'ultimate-lightbox'); ?></div>
					<div class="ulb-welcome-clear"></div>
					<div class='ulb-welcome-screen-next-button' data-nextaction='arrows_icons'><?php _e('Next Step', 'ultimate-lightbox'); ?></div>
					<div class='ulb-welcome-screen-previous-button' data-previousaction='add_lightbox'><?php _e('Previous Step', 'ultimate-lightbox'); ?></div>
					<div class='clear'></div>
				</div>
			</div>

			<div class='ulb-welcome-screen-box ulb-welcome-screen-arrows_icons' data-screen='arrows_icons'>
				<h2><?php _e( '3. Arrows & Icons', 'ultimate-lightbox' ); ?></h2>
				<div class='ulb-welcome-screen-box-content'>
					<table class='form-table ulb-welcome-screen-table'>
						<tr>
							<th scope='row'><?php _e( 'Arrows', 'ultimate-lightbox'); ?></th>
							<td class='ulb-welcome-screen-option'>
								<fieldset class='sap-setting-columns-3'>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='none'><span class='sap-admin-radio-button'></span> <span><?php _e( 'No Arrow', 'ultimate-lightbox' ); ?></span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='a' checked><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">b</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='c'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">d</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='e'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">f</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='g'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">h</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='i'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">j</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='k'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">l</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='m'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">n</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='o'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">p</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='q'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">r</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='A'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">B</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='E'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">F</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='G'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">H</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='I'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">J</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='K'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">L</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='M'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">N</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='O'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">P</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='arrow' value='Q'><span class='sap-admin-radio-button'></span> <span class="ulb-arrow">R</span></label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope='row'><?php _e( 'Icons', 'ultimate-lightbox'); ?></th>
							<td class='ulb-welcome-screen-option'>
								<fieldset class='sap-setting-columns-3'>
									<label class='sap-admin-input-container'><input type='radio' name='icons' value='a' checked><span class='sap-admin-radio-button'></span> <span class="ulb-exit">a</span><span class="ulb-autoplay">a</span><span class="ulb-zoom">a</span><span class="ulb-zoom_out">a</span><span class="ulb-download">a</span><span class="ulb-fullsize">a</span><span class="ulb-fullscreen">a</span><span class="ulb-regular_screen">a</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='icons' value='b'><span class='sap-admin-radio-button'></span> <span class="ulb-exit">b</span><span class="ulb-autoplay">b</span><span class="ulb-zoom">b</span><span class="ulb-zoom_out">b</span><span class="ulb-download">b</span><span class="ulb-fullsize">b</span><span class="ulb-fullscreen">b</span><span class="ulb-regular_screen">b</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='icons' value='c'><span class='sap-admin-radio-button'></span> <span class="ulb-exit">c</span><span class="ulb-autoplay">c</span><span class="ulb-zoom">c</span><span class="ulb-zoom_out">c</span><span class="ulb-download">c</span><span class="ulb-fullsize">c</span><span class="ulb-fullscreen">c</span><span class="ulb-regular_screen">c</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='icons' value='d'><span class='sap-admin-radio-button'></span> <span class="ulb-exit">d</span><span class="ulb-autoplay">d</span><span class="ulb-zoom">d</span><span class="ulb-zoom_out">d</span><span class="ulb-download">d</span><span class="ulb-fullsize">d</span><span class="ulb-fullscreen">d</span><span class="ulb-regular_screen">d</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='icons' value='e'><span class='sap-admin-radio-button'></span> <span class="ulb-exit">e</span><span class="ulb-autoplay">e</span><span class="ulb-zoom">e</span><span class="ulb-zoom_out">e</span><span class="ulb-download">e</span><span class="ulb-fullsize">e</span><span class="ulb-fullscreen">e</span><span class="ulb-regular_screen">e</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='icons' value='f'><span class='sap-admin-radio-button'></span> <span class="ulb-exit">f</span><span class="ulb-autoplay">f</span><span class="ulb-zoom">f</span><span class="ulb-zoom_out">f</span><span class="ulb-download">f</span><span class="ulb-fullsize">f</span><span class="ulb-fullscreen">f</span><span class="ulb-regular_screen">f</span></label>
									<label class='sap-admin-input-container'><input type='radio' name='icons' value='g'><span class='sap-admin-radio-button'></span> <span class="ulb-exit">g</span><span class="ulb-autoplay">g</span><span class="ulb-zoom">g</span><span class="ulb-zoom_out">g</span><span class="ulb-download">g</span><span class="ulb-fullsize">g</span><span class="ulb-fullscreen">g</span><span class="ulb-regular_screen">g</span></label>
								</fieldset>
							</td>
						</tr>
					</table>

					<div class='ulb-welcome-screen-save-arrows-icons-button'><?php _e('Save Options', 'ultimate-lightbox'); ?></div>
					<div class="ulb-welcome-clear"></div>
					<div class='ulb-welcome-screen-previous-button' data-previousaction='add_lightbox'><?php _e('Previous Step', 'ultimate-lightbox'); ?></div>
					<div class='ulb-welcome-screen-finish-button'><a href='admin.php?page=ulb-settings'><?php _e('Finish', 'ultimate-lightbox'); ?></a></div>
					<div class='clear'></div>
				</div>
			</div>
		
			<div class='ulb-welcome-screen-skip-container'>
				<a href='admin.php?page=ulb-settings'><div class='ulb-welcome-screen-skip-button'><?php _e('Skip Setup', 'ultimate-lightbox'); ?></div></a>
			</div>
		</div>

	<?php }
}


?>